<?php

namespace Mesd\FilterBundle\Entity;

/**
 * FilterEntity
 */
class FilterEntity
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
    private $ormName;

    /**
     * @var string
     */
    private $namespaceName;

    /**
     * @var string
     */
    private $databaseName;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $filterAssociation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $trailAssociation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categoryAssociation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $filterCategory;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $filter;

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filterAssociation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trailAssociation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categoryAssociation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filterCategory = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filter = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string name
     * @return FilterEntity
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
     * Set ormName
     *
     * @param string ormName
     * @return FilterEntity
     */
    public function setOrmName($ormName)
    {
        $this->ormName = $ormName;

        return $this;
    }

    /**
     * Get ormName
     *
     * @return string
     */
    public function getOrmName()
    {
        return $this->ormName;
    }

    /**
     * Set namespaceName
     *
     * @param string namespaceName
     * @return FilterEntity
     */
    public function setNamespaceName($namespaceName)
    {
        $this->namespaceName = $namespaceName;

        return $this;
    }

    /**
     * Get namespaceName
     *
     * @return string
     */
    public function getNamespaceName()
    {
        return $this->namespaceName;
    }

    /**
     * Set databaseName
     *
     * @param string databaseName
     * @return FilterEntity
     */
    public function setDatabaseName($databaseName)
    {
        $this->databaseName = $databaseName;

        return $this;
    }

    /**
     * Get databaseName
     *
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * Add filterAssociation
     *
     * @param \Mesd\FilterBundle\Entity\FilterAssociation $filterAssociation
     * @return FilterEntity
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

    /**
     * Add trailAssociation
     *
     * @param \Mesd\FilterBundle\Entity\FilterAssociation $trailAssociation
     * @return FilterEntity
     */
    public function addTrailAssociation(\Mesd\FilterBundle\Entity\FilterAssociation $trailAssociation)
    {
        $this->trailAssociation[] = $trailAssociation;

        return $this;
    }

    /**
     * Remove trailAssociation
     *
     * @param \Mesd\FilterBundle\Entity\FilterAssociation $trailAssociation
     */
    public function removeTrailAssociation(\Mesd\FilterBundle\Entity\FilterAssociation $trailAssociation)
    {
        $this->trailAssociation->removeElement($trailAssociation);
    }

    /**
     * Get trailAssociation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrailAssociation()
    {
        return $this->trailAssociation;
    }

    /**
     * Add categoryAssociation
     *
     * @param \Mesd\FilterBundle\Entity\FilterAssociation $categoryAssociation
     * @return FilterEntity
     */
    public function addCategoryAssociation(\Mesd\FilterBundle\Entity\FilterAssociation $categoryAssociation)
    {
        $this->categoryAssociation[] = $categoryAssociation;

        return $this;
    }

    /**
     * Remove categoryAssociation
     *
     * @param \Mesd\FilterBundle\Entity\FilterAssociation $categoryAssociation
     */
    public function removeCategoryAssociation(\Mesd\FilterBundle\Entity\FilterAssociation $categoryAssociation)
    {
        $this->categoryAssociation->removeElement($categoryAssociation);
    }

    /**
     * Get categoryAssociation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoryAssociation()
    {
        return $this->categoryAssociation;
    }

    /**
     * Add filterCategory
     *
     * @param \Mesd\FilterBundle\Entity\FilterCategory $filterCategory
     * @return FilterEntity
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

    /**
     * Add filter
     *
     * @param \Mesd\FilterBundle\Entity\Filter $filter
     * @return FilterEntity
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
}
