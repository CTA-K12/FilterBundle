<?php

namespace Mesd\FilterBundle\Model;

use Mesd\FilterBundle\Exception\MissingFilterException;

class FilterManager {

    private $securityContext;
    private $objectManager;
    private $bypassRoles;
    private $config;

    public function __construct($securityContext, $objectManager)
    {
        $this->securityContext = $securityContext;
        $this->objectManager   = $objectManager->getManager();
    }

    public function setBypassRoles( $bypassRoles )
    {
        $this->bypassRoles = $bypassRoles;

        return $this;
    }

    public function getBypassRoles()
    {
        return $this->bypassRoles;
    }

    public function setConfig( $config )
    {
        $this->config = $config;

        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function applyFilters($queryBuilder, $filtersToApply)
    {
        $user = $this->securityContext->getToken()->getUser();
        foreach ($this->bypassRoles as $bypassRole) {
            if ($this->securityContext->isGranted($bypassRole)) {

                return $queryBuilder;
            }
        }

        $filters = $user->getFilter();
        
        if (0 === count($filters)) {

            throw new MissingFilterException('Missing Filters');
        }

        $filtersByCategory = $this->sortFiltersByCategory($filtersToApply, $filters);

        if (count($filtersToApply) != count($filtersByCategory)) {

            throw new MissingFilterException('Missing Filters');
        }

        $queryBuilder = $this->applySortedFilters($queryBuilder, $filtersByCategory);

        return $queryBuilder;
    }
    
    protected function sortFiltersByCategory($filtersToApply, $filters) {
        $filtersByCategory = array();
        foreach ($filters as $filter) {
            $category = $filter->getFilterCategory()->getName();
            if(in_array($category, $filtersToApply)) {
                if (array_key_exists($category, $filtersByCategory)) {
                    $filtersByCategory[$category][] = $filter;
                } else {
                    $filtersByCategory[$category] = array($filter);
                }
            }
        }

        return $filtersByCategory;
    }
    
    protected function applySortedFilters($queryBuilder, $filtersByCategory)
    {
        $with = $this->getSortedFiltersWith($queryBuilder, $filtersByCategory);
        $rootAlias = $queryBuilder->getRootAlias();
        
        $entityNames = array();
        
        foreach ($filtersByCategory as $categoryString => $filters) {
            $category = $filters[0]->getFilterCategory();
            $mainAlias = $category->getFilterEntity()->getDatabaseName();
            if ($rootAlias !== $mainAlias) {

                throw new MisappliedFilter('filter entity does not match querybuilder alias');
            }
            $categoryId = $category->getId();
            $associations = $category->getFilterAssociation();
            $i = 0;
            $n = count($associations);
            foreach ($associations as $association) {
                $explodedTrail = explode('->', $association->getTrail());
                $alias = $mainAlias;
                $lastAlias = $explodedTrail[count($explodedTrail) - 1];
                foreach ($explodedTrail as $nextAlias) {
                    $newAlias = $nextAlias . $categoryId;
                    if (($lastAlias === $nextAlias) && (($n - 1) === $i)) {
                        $queryBuilder->join(
                            $alias . '.' . $nextAlias,
                            $newAlias,
                            'WITH',
                            $with[$categoryString]
                        );
                    } else {
                        $queryBuilder->join(
                            $alias . '.' . $nextAlias,
                            $newAlias
                        );
                        $alias = $newAlias;
                    }
                }
                $i++;
            }
        }

        return $queryBuilder;
    }
    
    protected function getSortedFiltersWith($queryBuilder, $filtersByCategory)
    {
        $with = array();
        foreach ($filtersByCategory as $categoryString => $filters) {
            $categoryWith = array();
            foreach ($filters as $filter) {
                $filterWith = array();
                foreach ($filter->getFilterRow() as $row) {
                    $rowWith = array();
                    foreach ($row->getFilterCell() as $cell) {
                        $cellWith = array();
                        $association = $cell->getFilterAssociation();
                        $alias = $association->getTrailEntity()->getDatabaseName() . $filter->getFilterCategory()->getId();
                        foreach ($cell->getSolvent() as $entityId) {
                            $cellWith[] = $queryBuilder->expr()->eq($alias, $entityId);
                        }
                        $rowWith[] = '(' . implode(' OR ', $cellWith) . ')';
                    }
                    $filterWith[] = '(' . implode(' AND ', $rowWith) . ')';
                }
                $categoryWith[] = '(' . implode(' OR ', $filterWith) . ')';
            }
            $with[$categoryString] = '(' . implode(' OR ', $categoryWith) . ')';
        }
        
        return $with;
    }
}
