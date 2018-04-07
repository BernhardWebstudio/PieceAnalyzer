<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PieceRepository")
 */
class Piece extends Item
{

    /**
     * @ORM\ManyToOne(targetEntity="Set", inversedBy="pieces")
     */
    private $set;

    /**
     * @ORM\Column(type="integer")
     */
    private $category;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $color;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $count;

    /**
     * Get the value of sets
     */ 
    public function getSet()
    {
        return $this->set;
    }

    /**
     * Set the value of sets
     *
     * @return  self
     */ 
    public function setSet(Set $set)
    {
        $this->set = $set;

        return $this;
    }

    /**
     * Get the value of category
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */ 
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of color
     */ 
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set the value of color
     *
     * @return  self
     */ 
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }
    
    public function getCount() {
        return $this->count;
    }
    
    public function setCount($count) {
        $this->count = $count;
        
        return $this;
    }
}
