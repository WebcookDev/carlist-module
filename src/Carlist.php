<?php

/**
 * This file is part of the Carlist module for webcms2.
 * Copyright (c) @see LICENSE
 */

namespace WebCMS\CarlistModule;

/**
 * Description of carl;ist
 *
 * @author Jakub Sanda <jakub.sanda@webcook.cz>
 */
class Carlist extends \WebCMS\Module
{
	/**
	 * [$name description]
	 * @var string
	 */
    protected $name = 'Carlist';
    
    /**
     * [$author description]
     * @var string
     */
    protected $author = 'Jakub Sanda';
    
    protected $searchable = true;

    /**
     * [$presenters description]
     * @var array
     */
    protected $presenters = array(
		array(
		    'name' => 'Carlist',
		    'frontend' => true,
		    'parameters' => true
		),
		array(
		    'name' => 'Settings',
		    'frontend' => false
		)
    );

    public function __construct()
    {
        
    }

    public function search(\Doctrine\ORM\EntityManager $em, $phrase, \WebCMS\Entity\Language $language)
    {
        
    }
}
