<?php

/**
 * This file is part of the Carlist module for webcms2.
 * Copyright (c) @see LICENSE
 */

namespace WebCMS\CarlistModule\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="carlist_car")
 */
class Car extends \WebCMS\Entity\Entity
{
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="cars") 
     */
    private $category;

    /**
     * @gedmo\Slug(fields={"name"})
     * @orm\Column(length=255, unique=true)
     */
    private $slug;

	/**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
	private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $priceInfo;

    /**
     * @orm\Column(type="text", nullable=true)
     */
    private $shortInfo;

    /**
     * @orm\Column(type="text", nullable=true)
     */
    private $info;

    /**
     * @orm\Column(type="text", nullable=true)
     */
    private $equipment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $enginePower;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $engineVolume;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dateOfManufacture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $drivenKm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fuelType;

    /**
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="car") 
     * @var Array
     */
    private $photos;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hide;

    /**
     * @ORM\Column(type="boolean")
     */
    private $top;

    /**
     * @ORM\Column(type="boolean")
     */
    private $homepage;


    public function __construct()
    {
        $this->hide = false;
        $this->top = false;
        $this->homepage = false;
    }

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
     * Gets the value of price.
     *
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets the value of price.
     *
     * @param mixed $price the price
     *
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Sets the value of info.
     *
     * @param mixed $info the info
     *
     * @return self
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Gets the value of info.
     *
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    public function getPhotos() 
    {
        return $this->photos;
    }

    public function setPhotos(Array $photos) 
    {
        $this->photos = $photos;

        return $this;
    }

    public function getDefaultPhoto(){
        foreach($this->getPhotos() as $photo){
            if($photo->getMain()){
                return $photo;
            }
        }
        
        return NULL;
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
     * Gets the value of hide.
     *
     * @return mixed
     */
    public function getHide()
    {
        return $this->hide;
    }

    /**
     * Sets the value of hide.
     *
     * @param mixed $hide the hide
     *
     * @return self
     */
    public function setHide($hide)
    {
        $this->hide = $hide;

        return $this;
    }

    /**
     * Gets the value of top.
     *
     * @return mixed
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Sets the value of top.
     *
     * @param mixed $top the top
     *
     * @return self
     */
    public function setTop($top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * Gets the value of homepage.
     *
     * @return mixed
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * Sets the value of homepage.
     *
     * @param mixed $homepage the homepage
     *
     * @return self
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;

        return $this;
    }

    /**
     * Sets the value of category.
     *
     * @param mixed $category the category
     *
     * @return self
     */
    public function getCategory() 
    {
        return $this->category;
    }

    /**
     * Sets the value of category.
     *
     * @param mixed $category the category
     *
     * @return self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    public function getDrivenKm()
    {
        return $this->drivenKm;
    }
    
    public function setDrivenKm($drivenKm)
    {
        $this->drivenKm = $drivenKm;
        return $this;
    }

    public function getEnginePower()
    {
        return $this->enginePower;
    }
    
    public function setEnginePower($enginePower)
    {
        $this->enginePower = $enginePower;
        return $this;
    }

    public function getEngineVolume()
    {
        return $this->engineVolume;
    }
    
    public function setEngineVolume($engineVolume)
    {
        $this->engineVolume = $engineVolume;
        return $this;
    }

    public function getPriceInfo()
    {
        return $this->priceInfo;
    }
    
    public function setPriceInfo($priceInfo)
    {
        $this->priceInfo = $priceInfo;
        return $this;
    }

    public function getShortInfo()
    {
        return $this->shortInfo;
    }
    
    public function setShortInfo($shortInfo)
    {
        $this->shortInfo = $shortInfo;
        return $this;
    }

    public function getEquipment()
    {
        return $this->equipment;
    }
    
    public function setEquipment($equipment)
    {
        $this->equipment = $equipment;
        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }
    
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    public function getDateOfManufacture()
    {
        return $this->dateOfManufacture;
    }
    
    public function setDateOfManufacture($dateOfManufacture)
    {
        $this->dateOfManufacture = $dateOfManufacture;
        return $this;
    }

    public function getFuelType()
    {
        return $this->fuelType;
    }
    
    public function setFuelType($fuelType)
    {
        $this->fuelType = $fuelType;
        return $this;
    }

}