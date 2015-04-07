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

            if (isset($parameters['parameters'][1])) {
                $this->car = $this->repository->findOneBy(array(
                    'slug' => $parameters['parameters'][1]
                ));

                if (!is_object($this->car)) {
                    throw new \Nette\Application\BadRequestException();
                }
            }            
        }
    }


    public function renderDefault($id)
    {   
        if ($this->car) {
            $this->template->car = $this->car;
            $this->template->carForm = $this->createComponentForm('form', $this, $this->actualPage);
            $this->template->setFile(APP_DIR . '/templates/carlist-module/Carlist/detail.latte');
        }
        $this->template->id = $id;
        $this->template->cars = $this->cars;
        $this->template->categories = $this->categories;
        $this->template->category = $this->category;

        $this->template->carPage = $this->em->getRepository('WebCMS\Entity\Page')->findOneBy(array(
            'moduleName' => 'Carlist',
            'presenter' => 'Carlist'
        ));
    }

    public function createComponentForm($name, $context = null, $fromPage = null) 
    {

        if($context != null){

            $form = new UI\Form();

            $form->getElementPrototype()->action = $context->link('default', array(
                'path' => $fromPage->getPath(),
                'abbr' => $context->abbr,
                'parameters' => array($this->car->getCategory()->getSlug(), $this->car->getSlug()),
                'do' => 'form-submit'
            ));

            $form->setTranslator($context->translator);
            $form->setRenderer(new BootstrapRenderer);
            
            $form->getElementPrototype()->class = 'form-horizontal contact-agent-form';
            
        }else{
            $form = $this->createForm('form-submit', 'default', $context);
        }

        $form->addText('name', 'Name')
        ->setAttribute('placeholder', 'Vaše jméno')
        ->setRequired();
        $form->addText('email', 'E-mail')
            ->setAttribute('placeholder', 'Váš e-mail')
            ->addRule(UI\Form::EMAIL, 'Email is not valid')
            ->setRequired();
        $form->addText('phone', 'Phone number')
            ->setAttribute('placeholder', 'Váš telefon');
        $form->addHidden('carName', $this->car->getName());

        $form->addTextArea('text', 'Text')->setRequired();

        $form->addSubmit('submit', 'Send demand')->setAttribute('class', 'btn btn-success');
        $form->onSuccess[] = callback($this, 'formSubmitted');

        return $form;
    }

    public function formSubmitted($form)
    {

        $values = $form->getValues();

        $mail = new \Nette\Mail\Message;
        $infoMail = $this->settings->get('Info email', 'basic', 'text')->getValue();
        $mail->addTo($infoMail);
        
        $domain = str_replace('www.', '', $this->getHttpRequest()->url->host);
        
        if($domain !== 'localhost') $mail->setFrom('no-reply@' . $domain);
        else $mail->setFrom('no-reply@test.cz'); // TODO move to settings

        $mailBody = '<h1>'.$values->carName.'</h1>';
        $mailBody .= '<p><strong>Jméno: </strong>'.$values->name.'</p>';
        $mailBody .= '<p><strong>Email: </strong>'.$values->email.'</p>';
        $mailBody .= '<p><strong>Telefon: </strong>'.$values->phone.'</p>';
        $mailBody .= '<p><strong>Text: </strong>'.$values->text.'</p>';

        $mail->setSubject('Poptávka automobilu '.$values->carName);
        $mail->setHtmlBody($mailBody);

        try {
            $mail->send();  
            $this->flashMessage('Demand form has been sent', 'success');
        } catch (\Exception $e) {
            $this->flashMessage('Cannot send email.', 'danger');                    
        }
       

        $httpRequest = $this->getContext()->getService('httpRequest');

        $url = $httpRequest->getReferer();
        $url->appendQuery(array(self::FLASH_KEY => $this->getParam(self::FLASH_KEY)));

        $this->redirectUrl($url->absoluteUrl);
        
    }

    public function homepageBox($context, $fromPage)
    {
        $template = $context->createTemplate();
        $allCars = $context->em->getRepository('WebCMS\CarlistModule\Entity\Car')->findBy(array(
            'hide' => false
        ));
        $template->cars = $context->em->getRepository('WebCMS\CarlistModule\Entity\Car')->findBy(array(
            'hide' => false,
            'homepage' => true
        ));
        $template->newCars = $context->em->getRepository('WebCMS\NewsModule\Doctrine\Actuality')->findAll();
        $template->carPage = $context->em->getRepository('WebCMS\Entity\Page')->findOneBy(array(
            'moduleName' => 'Carlist',
            'presenter' => 'Carlist',
            'language' => $fromPage->getLanguage()
        ));
        $template->link = $context->link(':Frontend:Carlist:Carlist:default', array(
            'id' => $fromPage->getId(),
            'path' => $fromPage->getPath(),
            'abbr' => $context->abbr
        ));
        $template->abbr = $context->abbr;
        $template->countOfCars = count($allCars);
        $template->setFile(APP_DIR . '/templates/carlist-module/Carlist/homepageBox.latte');
        return $template;  
    }

}
