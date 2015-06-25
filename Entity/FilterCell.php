<?php

namespace Mesd\FilterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilterCell
 */
class FilterCell
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \Mesd\FilterBundle\Entity\FilterAssociation
     */
    private $filterAssociation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $filterRow;

    public function __toString()
    {
        return $this->getDescription();
    }

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
     * Set value
     *
     * @param string $value
     * @return FilterCell
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return FilterCell
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
     * Set filterAssociation
     *
     * @param \Mesd\FilterBundle\Entity\FilterAssociation $filterAssociation
     * @return FilterCell
     */
    public function setFilterAssociation(\Mesd\FilterBundle\Entity\FilterAssociation $filterAssociation = null)
    {
        $this->filterAssociation = $filterAssociation;

        return $this;
    }

    /**
     * Get filterAssociation
     *
     * @return \Mesd\FilterBundle\Entity\FilterAssociation 
     */
    public function getFilterAssociation()
    {
        return $this->filterAssociation;
    }

    /**
     * Add filterRow
     *
     * @param \Mesd\FilterBundle\Entity\FilterRow $filterRow
     * @return FilterCell
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
