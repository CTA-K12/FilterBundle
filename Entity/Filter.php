<?php

namespace Mesd\FilterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Filter
 */
class Filter
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
     * @var \Mesd\FilterBundle\Entity\FilterCategory
     */
    private $filterCategory;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $filterRow;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filterRow = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Filter
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
     * Set filterCategory
     *
     * @param \Mesd\FilterBundle\Entity\FilterCategory $filterCategory
     * @return Filter
     */
    public function setFilterCategory(\Mesd\FilterBundle\Entity\FilterCategory $filterCategory = null)
    {
        $this->filterCategory = $filterCategory;

        return $this;
    }

    /**
     * Get filterCategory
     *
     * @return \Mesd\FilterBundle\Entity\FilterCategory 
     */
    public function getFilterCategory()
    {
        return $this->filterCategory;
    }

    /**
     * Add filterRow
     *
     * @param \Mesd\FilterBundle\Entity\FilterRow $filterRow
     * @return Filter
     */
    public function addFilterRow(\Mesd\FilterBundle\Entity\FilterRow $filterRow)
    {
        $this->filterRow[] = $filterRow;

        return $this;
    }

    /**
     * Remove filterRow
     *
     * @param \Mesd\FilterBundle\Entity\FilterRow $filterRow
     */
    public function removeFilterRow(\Mesd\FilterBundle\Entity\FilterRow $filterRow)
    {
        $this->filterRow->removeElement($filterRow);
    }

    /**
     * Get filterRow
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFilterRow()
    {
        return $this->filterRow;
    }
}
