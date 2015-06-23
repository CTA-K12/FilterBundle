<?php

namespace Mesd\FilterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilterJoin
 */
class FilterJoin
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $value;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $filterCell;

    public function __toString()
    {
        return $this->getDescription();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filterCell = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param integer $value
     * @return FilterJoin
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return FilterJoin
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add filterCell
     *
     * @param \Mesd\FilterBundle\Entity\FilterCell $filterCell
     * @return FilterJoin
     */
    public function addFilterCell(\Mesd\FilterBundle\Entity\FilterCell $filterCell)
    {
        $this->filterCell[] = $filterCell;

        return $this;
    }

    /**
     * Remove filterCell
     *
     * @param \Mesd\FilterBundle\Entity\FilterCell $filterCell
     */
    public function removeFilterCell(\Mesd\FilterBundle\Entity\FilterCell $filterCell)
    {
        $this->filterCell->removeElement($filterCell);
    }

    /**
     * Get filterCell
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFilterCell()
    {
        return $this->filterCell;
    }
}
