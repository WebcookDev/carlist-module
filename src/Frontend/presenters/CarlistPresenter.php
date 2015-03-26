<?php

/**
 * This file is part of the Carlist module for webcms2.
 * Copyright (c) @see LICENSE
 */

namespace FrontendModule\CarlistModule;

use Nette\Application\UI;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
use WebCMS\CarlistModule\Entity\Car;
use WebCMS\CarlistModule\Entity\Category;

/**
 * Description of CarlistPresenter
 *
 * @author Jakub Sanda <jakub.sanda@webcook.cz>
 */
class CarlistPresenter extends BasePresenter
{
    private $repository;

    private $categoryRepository;

    private $car;

    private $cars;

    private $category;

    private $categories;
    
    protected function startup() 
    {
        parent::startup();

        $this->repository = $this->em->getRepository('WebCMS\CarlistModule\Entity\Car');
        $this->categoryRepository = $this->em->getRepository('WebCMS\CarlistModule\Entity\Category');
    }

    protected function beforeRender()
    {
        parent::beforeRender(); 
    }

    public function actionDefault($id)
    {
        $this->cars = $this->repository->findAll();
        $this->categories = $this->categoryRepository->findAll();

        $parameters = $this->getParameter();

        if (count($parameters['parameters']) > 0) {
            $this->category = $this->categoryRepository->findOneBy(array(
                'slug' => $parameters['parameters'][0]
            ));
            $this->cars = $this->repository->findBy(array(
                'category' => $this->category
            ));

            // if (!is_object($this->product)) {
            //     throw new \Nette\Application\BadRequestException();
            // }
        }
    }


    public function renderDefault($id)
    {   
        $this->template->id = $id;
        $this->template->cars = $this->cars;
        $this->template->categories = $this->categories;
    }

}
