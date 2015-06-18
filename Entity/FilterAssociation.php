<?php

namespace Mesd\FilterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilterAssociation
 */
class FilterAssociation
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
    private $trail;

    /**
     * @var \Mesd\FilterBundle\Entity\FilterEntity
     */
    private $filterEntity;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $filterJoin;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $filterCategory;

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filterJoin = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filterCategory = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return FilterAssociation
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
     * Set trail
     *
     * @param string $trail
     * @return FilterAssociation
     */
    public function setTrail($trail)
    {
        $this->trail = $trail;

        return $this;
    }

    /**
     * Get trail
     *
     * @return string 
     */
    public function getTrail()
    {
        return $this->trail;
    }

    /**
     * Set filterEntity
     *
     * @param \Mesd\FilterBundle\Entity\FilterEntity $filterEntity
     * @return FilterAssociation
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
     * Add filterJoin
     *
     * @param \Mesd\FilterBundle\Entity\FilterJoin $filterJoin
     * @return FilterAssociation
     */
    public function addFilterJoin(\Mesd\FilterBundle\Entity\FilterJoin $filterJoin)
    {
        $this->filterJoin[] = $filterJoin;

        return $this;
    }

    /**
     * Remove filterJoin
     *
     * @param \Mesd\FilterBundle\Entity\FilterJoin $filterJoin
     */
    public function removeFilterJoin(\Mesd\FilterBundle\Entity\FilterJoin $filterJoin)
    {
        $this->filterJoin->removeElement($filterJoin);
    }

    /**
     * Get filterJoin
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFilterJoin()
    {
        return $this->filterJoin;
    }

    /**
     * Add filterCategory
     *
     * @param \Mesd\FilterBundle\Entity\FilterCategory $filterCategory
     * @return FilterAssociation
     */
    public function addFilterCategory(\Mesd\FilterBundle\Entity\FilterCategory $filterCategory)
    {
        $this->filterCategory[] = $filterCategory;

        return $this;
    }

    /**
     * Remove filterCategory
     *
     * @param \Mesd\FilterBundle\Entity\FilterCategory $filterCategory
     */
    public function removeFilterCategory(\Mesd\FilterBundle\Entity\FilterCategory $filterCategory)
    {
        $this->filterCategory->removeElement($filterCategory);
    }

    /**
     * Get filterCategory
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFilterCategory()
    {
        return $this->filterCategory;
    }
}
