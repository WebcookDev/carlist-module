<?php

/**
 * This file is part of the Carlist module for webcms2.
 * Copyright (c) @see LICENSE
 */

namespace AdminModule\CarlistModule;

/**
 * Description of
 *
 * @author Jakub Sanda <jakub.sanda@webcook.cz>
 */
class BasePresenter extends \AdminModule\BasePresenter
{	
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
}