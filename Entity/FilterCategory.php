<?php

namespace Mesd\FilterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilterCategory
 */
class FilterCategory
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $codeName;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $filter;

    /**
     * @var \Mesd\FilterBundle\Entity\FilterEntity
     */
    private $filterEntity;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $filterAssociation;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filter = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filterAssociation = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return FilterCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set codeName
     *
     * @param string $codeName
     * @return FilterCategory
     */
    public function setCodeName($codeName)
    {
        $this->codeName = $codeName;

        return $this;
    }

    /**
     * Get codeName
     *
     * @return string 
     */
    public function getCodeName()
    {
        return $this->codeName;
    }

    /**
     * Add filter
     *
     * @param \Mesd\FilterBundle\Entity\Filter $filter
     * @return FilterCategory
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
     * Set filterEntity
     *
     * @param \Mesd\FilterBundle\Entity\FilterEntity $filterEntity
     * @return FilterCategory
     */
    public function setFilterEntity(\Mesd\FilterBundle\Entity\FilterEntity $filterEntity = null)
    {
        $this->filterEntity = $filterEntity;

        return $this;
    }

    /**
     * Get filterEntity
     *
     * @return \Mesd\FilterBundle\Entity\FilterEntity 
     */
    public function getFilterEntity()
    {
        return $this->filterEntity;
    }

    /**
     * Add filterAssociation
     *
     * @param \Mesd\FilterBundle\Entity\FilterAssociation $filterAssociation
     * @return FilterCategory
     */
    public function addFilterAssociation(\Mesd\FilterBundle\Entity\FilterAssociation $filterAssociation)
    {
        $this->filterAssociation[] = $filterAssociation;

        return $this;
    }

    /**
     * Remove filterAssociation
     *
     * @param \Mesd\FilterBundle\Entity\FilterAssociation $filterAssociation
     */
    public function removeFilterAssociation(\Mesd\FilterBundle\Entity\FilterAssociation $filterAssociation)
    {
        $this->filterAssociation->removeElement($filterAssociation);
    }

    /**
     * Get filterAssociation
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFilterAssociation()
    {
        return $this->filterAssociation;
    }
}
