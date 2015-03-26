<?php

/**
 * This file is part of the Carlist module for webcms2.
 * Copyright (c) @see LICENSE
 */

namespace WebCMS\CarlistModule\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="carlist_category")
 */
class Category extends \WebCMS\Entity\Entity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @gedmo\Slug(fields={"name"})
     * @orm\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Car", mappedBy="category") 
     * @var Array
     */
    private $cars;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brandThumb;


    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of slug.
     *
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Gets the cars of cars.
     *
     * @return mixed
     */
    public function getCars()
    {
        return $this->cars;
    }

    /**
     * Sets the value of cars.
     *
     * @param mixed $cars the cars
     *
     * @return self
     */
    public function setCars($cars)
    {
        $this->cars = $cars;

        return $this;
    }

    /**
     * Gets the brandThumb of brandThumb.
     *
     * @return mixed
     */
    public function getBrandThumb()
    {
        return $this->brandThumb;
    }

    /**
     * Sets the value of brandThumb.
     *
     * @param mixed $brandThumb the brandThumb
     *
     * @return self
     */
    public function setBrandThumb($brandThumb)
    {
        $this->brandThumb = $brandThumb;

        return $this;
    }




}
