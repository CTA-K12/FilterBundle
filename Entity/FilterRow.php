<?php

namespace Mesd\FilterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilterRow
 */
class FilterRow
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var array
     */
    private $solvent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $filter;

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
        $this->filter = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set description
     *
     * @param string $description
     * @return FilterRow
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
     * Set solvent
     *
     * @param array $solvent
     * @return FilterRow
     */
    public function setSolvent($solvent)
    {
        $this->solvent = $solvent;

        return $this;
    }

    /**
     * Get solvent
     *
     * @return array 
     */
    public function getSolvent()
    {
        return $this->solvent;
    }

    /**
     * Add filter
     *
     * @param \Mesd\FilterBundle\Entity\Filter $filter
     * @return FilterRow
     */
    public function addFilter(\Mesd\FilterBundle\Entity\Filter $filter)
    {
        $this->filter[] = $filter;

        return $this;
    }

    /**
     * Remove filter
     *
     * @param \Mesd\FilterBundle\Entity\Filter $filter
     */
    public function removeFilter(\Mesd\FilterBundle\Entity\Filter $filter)
    {
        $this->filter->removeElement($filter);
    }

    /**
     * Get filter
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Add filterCell
     *
     * @param \Mesd\FilterBundle\Entity\FilterCell $filterCell
     * @return FilterRow
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
