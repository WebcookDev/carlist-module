<?php

/**
 * This file is part of the Carlist module for webcms2.
 * Copyright (c) @see LICENSE
 */

namespace AdminModule\CarlistModule;

use Nette\Forms\Form;
use WebCMS\CarlistModule\Entity\Car;
use WebCMS\CarlistModule\Entity\Photo;
use WebCMS\CarlistModule\Entity\Category;

/**
 * Main controller
 *
 * @author Jakub Sanda <jakub.sanda@webcook.cz>
 */
class CarlistPresenter extends BasePresenter
{
    private $car;

    private $category;

    protected function startup()
    {
    	parent::startup();
    }

    protected function beforeRender()
    {
	   parent::beforeRender();
    }

    public function actionDefault($idPage)
    {

    }

    public function renderDefault($idPage)
    {
        $this->reloadContent();
        $this->template->idPage = $idPage;
    }

    public function actionCategories($idPage)
    {
       
    }

    public function renderCategories($idPage)
    {
        $this->reloadContent();
        $this->template->idPage = $idPage;
    }

    protected function createComponentGrid($name)
    {
        $grid = $this->createGrid($this, $name, "\WebCMS\CarlistModule\Entity\Car");

        $grid->addColumnText('name', 'Name')->setSortable();

        $grid->addColumnText('engineVolume', 'Engine volume')->setSortable();

        $grid->addColumnText('enginePower', 'Engine power')->setSortable();

        $grid->addColumnText('dateOfManufacture', 'Date of manufacture')->setSortable();

        $grid->addColumnText('fuelType', 'Fuel type')->setSortable();

        $grid->addColumnText('drivenKm', 'Driven km')->setSortable();

        $grid->addColumnText('color', 'Color')->setSortable();

        $grid->addColumnText('category', 'Category')->setCustomRender(function($item) {
            return $item->getCategory()->getName();
        })->setSortable();

        $grid->addColumnText('price', 'Price')->setSortable();

        $grid->addColumnText('homepage', 'Added to homepage')->setCustomRender(function($item) {
            if ($item->getHomepage()) {
                return 'yes';
            } else {
                return 'no';
            }
        })->setSortable();

        $grid->addActionHref("update", 'Edit', 'update', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => array('btn', 'btn-primary', 'ajax')));
        $grid->addActionHref("addToHomepage", 'Add to homepage', 'addToHomepage', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => array('btn', 'btn-primary', 'ajax')));
        $grid->addActionHref("delete", 'Delete', 'delete', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => array('btn', 'btn-danger') , 'data-confirm' => 'Are you sure you want to delete this item?'));

        return $grid;
    }

    public function actionUpdate($id, $idPage)
    {
        $this->reloadContent();

        $this->car = $id ? $this->em->getRepository('\WebCMS\CarlistModule\Entity\Car')->find($id) : "";

        $this->template->idPage = $idPage;
        $this->template->car = $this->car;
    }

    public function actionAddToHomepage($id, $idPage)
    {
        $this->car = $this->em->getRepository('\WebCMS\CarlistModule\Entity\Car')->find($id);
        $this->car->setHomepage($this->car->getHomepage() ? false : true);

        $this->em->flush();

        $this->flashMessage('Car has been changed', 'success');
        $this->forward('default', array(
            'idPage' => $this->actualPage->getId()
        ));
    }

    public function actionDelete($id){

        $car = $this->em->getRepository('\WebCMS\CarlistModule\Entity\Car')->find($id);
        $this->em->remove($car);
        $this->em->flush();
        
        $this->flashMessage('Car has been removed.', 'success');
        
        if(!$this->isAjax()){
            $this->redirect('default', array(
                'idPage' => $this->actualPage->getId()
            ));
        }
    }

    protected function createComponentCategoriesGrid($name)
    {
        $grid = $this->createGrid($this, $name, "\WebCMS\CarlistModule\Entity\Category");

        $grid->addColumnText('name', 'Name')->setSortable();

        $grid->addActionHref("updateCategory", 'Edit', 'updateCategory', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => array('btn', 'btn-primary', 'ajax')));

        return $grid;
    }

    public function actionUpdateCategory($id, $idPage)
    {
        $this->reloadContent();

        $this->category = $id ? $this->em->getRepository('\WebCMS\CarlistModule\Entity\Category')->find($id) : "";

        $this->template->idPage = $idPage;
    }

    protected function createComponentCategoryForm()
    {
        $form = $this->createForm();

        $form->addText('name', 'Name')->setRequired();

        $form->addSubmit('submit', 'Save')->setAttribute('class', 'btn btn-success');
        $form->onSuccess[] = callback($this, 'categoryFormSubmitted');
 
        if (is_object($this->category)) {
            $form->setDefaults($this->category->toArray());
        }
        
        return $form;
    }
    
    public function categoryFormSubmitted($form)
    {
        $values = $form->getValues();

        if(!is_object($this->category)){
            $this->category = new Category;
            $this->em->persist($this->category);
        }
        
        foreach ($values as $key => $value) {
            $setter = 'set' . ucfirst($key);
            $this->category->$setter($value);
        }

        $this->em->flush();
        $this->flashMessage('Category has been added/updated.', 'success');
        
        $this->forward('categories', array(
            'idPage' => $this->actualPage->getId()
        ));
    }

    protected function createComponentCarForm()
    {
        $form = $this->createForm();

        $categories = $this->em->getRepository('\WebCMS\CarlistModule\Entity\Category')->findAll();
        $categoriesForSelect = array();
        if ($categories) {
            foreach ($categories as $category) {
                $categoriesForSelect[$category->getId()] = $category->getName();
            }
        }

        $form->addText('name', 'Name')->setRequired();
        $form->addSelect('category', 'Category')->setItems($categoriesForSelect);
        $form->addText('engineVolume', 'Engine volume');
        $form->addText('enginePower', 'Engine power');
        $form->addText('fuelType', 'Fuel type');
        $form->addText('drivenKm', 'Driven km');
        $form->addText('color', 'color');
        $form->addTextArea('equipment', 'Equipment');
        $form->addText('dateOfManufacture', 'Date of manufacture');
        $form->addText('price', 'Price');
        $form->addText('priceInfo', 'Price info');
                
        $form->addCheckbox('hide', 'Hide');

        $form->addSubmit('submit', 'Save')->setAttribute('class', 'btn btn-success');
        $form->onSuccess[] = callback($this, 'carFormSubmitted');
 
        if (is_object($this->car)) {
            $form->setDefaults($this->car->toArray());
        }
        
        return $form;
    }
    
    public function carFormSubmitted($form)
    {
        $values = $form->getValues();

        if(!is_object($this->car)){
            $this->car = new Car;
            $this->em->persist($this->car);
        }else{
            // delete old photos and save new ones
            $qb = $this->em->createQueryBuilder();
            $qb->delete('WebCMS\CarlistModule\Entity\Photo', 'l')
                    ->where('l.car = ?1')
                    ->setParameter(1, $this->car)
                    ->getQuery()
                    ->execute();
        }

        $category = $this->em->getRepository('\WebCMS\CarlistModule\Entity\Category')->find($values->category);
        
        $this->car->setName($values->name);
        $this->car->setCategory($category);

        $this->car->setEngineVolume($values->engineVolume);
        $this->car->setEnginePower($values->enginePower);
        $this->car->setFuelType($values->fuelType);
        $this->car->setDrivenKm($values->drivenKm);
        $this->car->setColor($values->color);
        $this->car->setEquipment($values->equipment);
        $this->car->setDateOfManufacture($values->dateOfManufacture);

        $this->car->setPrice($values->price);
        $this->car->setPriceInfo($values->priceInfo);
        $this->car->setHide($values->hide);
            
        if(array_key_exists('files', $_POST)){
            $counter = 0;
            if(array_key_exists('fileDefault', $_POST)) $default = intval($_POST['fileDefault'][0]) - 1;
            else $default = -1;
            
            foreach($_POST['files'] as $path){

                $photo = new \WebCMS\CarlistModule\Entity\Photo;
                $photo->setName($_POST['fileNames'][$counter]);
                
                if($default === $counter){
                    $photo->setMain(TRUE);
                }else{
                    $photo->setMain(FALSE);
                }
                
                $photo->setPath($path);
                $photo->setcar($this->car);
                $photo->setCreated(new \DateTime);

                $this->em->persist($photo);

                $counter++;
            }
        }

        $this->em->flush();
        $this->flashMessage('Car has been added/updated.', 'success');
        
        $this->forward('default', array(
            'idPage' => $this->actualPage->getId()
        ));
    }
}