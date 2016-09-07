<?php

namespace Mesd\FilterBundle\Model;

use Doctrine\ORM\QueryBuilder;

use Mesd\FilterBundle\Exception\MisappliedFilterException;
use Mesd\FilterBundle\Exception\MissingFilterException;

class FilterManager
{

    private $securityContext;
    private $bypassRoles;
    private $config;

    public function __construct($securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function setBypassRoles($bypassRoles)
    {
        $this->bypassRoles = $bypassRoles;

        return $this;
    }

    public function getBypassRoles()
    {
        return $this->bypassRoles;
    }

    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }
    
    public function applyFilters(QueryBuilder $queryBuilder, $filtersToApply)
    {
        $user = $this->securityContext->getToken()->getUser();
        foreach ($this->bypassRoles as $bypassRole) {
            if ($this->securityContext->isGranted($bypassRole)) {
                return $queryBuilder;
            }
        }

        $filters = $user->getFilter();
        
        $filtersByCategory = $this->sortFiltersByCategory($filtersToApply, $filters);

        if (count($filtersToApply) != count($filtersByCategory)) {
            $expected = implode(', ', $filtersToApply);
            $actual = implode(', ', array_keys($filtersByCategory));
            throw new MissingFilterException($expected . ' filters expected but got ' . $actual);
        }

        $queryBuilder = $this->applySortedFilters($queryBuilder, $filtersByCategory);

        return $queryBuilder;
    }

    protected function sortFiltersByCategory($filtersToApply, $filters)
    {
        $filtersByCategory = array();
        foreach ($filters as $filter) {
            $category = $filter->getFilterEntity()->getName();
            if (in_array($category, $filtersToApply)) {
                if (array_key_exists($category, $filtersByCategory)) {
                    $filtersByCategory[$category][] = $filter;
                } else {
                    $filtersByCategory[$category] = array($filter);
                }
            }
        }

        return $filtersByCategory;
    }
    
    protected function applySortedFilters(QueryBuilder $queryBuilder, $filtersByCategory)
    {
        $rootAlias = $queryBuilder->getRootAlias();
        $rootNamespaces = $queryBuilder->getRootEntities();
        $with = $this->getSortedFiltersWith($queryBuilder, $filtersByCategory, $rootAlias);
        foreach ($filtersByCategory as $categoryString => $filters) {
            $category = $filters[0]->getFilterEntity();
            $mainAlias = $rootAlias;
            $mainNamespace = $category->getNamespaceName();
            if (!in_array($mainNamespace, $rootNamespaces)) {
                throw new MisappliedFilterException('filter entity ' . $mainNamespace
                    . ' does not match querybuilder entities ' . implode(', ', $rootNamespaces));
            }
            $categoryId = $category->getId();
            $associations = $category->getFilterAssociation();
            $alreadyJoined = array();
            foreach ($associations as $association) {
                $explodedTrail = explode('->', $association->getTrail());
                $alias = $mainAlias;
                foreach ($explodedTrail as $nextAlias) {
                    if ('id' === $nextAlias) {
                        $queryBuilder->andWhere($with['on'][$categoryString]);
                    } else {
                        $newAlias = $nextAlias . $categoryId;
                        if (
                            array_key_exists($newAlias, $with['inner'])
                            && !array_key_exists($newAlias, $alreadyJoined)
                        ) {
                            $queryBuilder->leftJoin(
                                $alias . '.' . $nextAlias,
                                $newAlias
                            );
                            $alreadyJoined[$newAlias] = $newAlias;
                        }
                        $alias = $newAlias;
                    }
                }
            }
            $queryBuilder->andWhere($with['on'][$categoryString]);
        }

        return $queryBuilder;
    }
    
    protected function getSortedFiltersWith(QueryBuilder $queryBuilder, $filtersByCategory, $rootAlias)
    {
        $with = array(
            'inner' => array(),
            'on' => array(),
            'last' => '',
        );
        foreach ($filtersByCategory as $categoryString => $filters) {
            $categoryWith = array();
            foreach ($filters as $filter) {
                $filterWith = array();
                foreach ($filter->getFilterRow() as $row) {
                    $rowWith = array();
                    foreach ($row->getFilterCell() as $cell) {
                        $cellWith = array();
                        $association = $cell->getFilterAssociation();
                        $trail = $association->getTrail();
                        if ('id' === $trail) {
                            $alias = $rootAlias . '.id';
                            $endAlias = 'id';
                            $explodedTrail = array('id');
                        } else {
                            $explodedTrail = explode('->', $trail);
                            $n = count($explodedTrail);
                            $endAlias = $explodedTrail[$n - 1];
                            $alias = $endAlias
                                . $filter->getFilterEntity()->getId();
                        }
                        foreach ($cell->getSolvent() as $entityId) {
                            foreach ($explodedTrail as $trailItem) {
                                $trailItem .= $filter->getFilterEntity()->getId();
                                if (!array_key_exists($trailItem, $with['inner'])) {
                                    $with['inner'][$trailItem] = $trailItem;
                                }
                                $with['last'] = $trailItem;
                            }
                            $cellWith[] = $queryBuilder->expr()->eq($alias, $entityId);
                        }
                        $rowWith[] = '(' . implode(' OR ', $cellWith) . ')';
                    }
                    $filterWith[] = '(' . implode(' AND ', $rowWith) . ')';
                }
                $categoryWith[] = '(' . implode(' OR ', $filterWith) . ')';
            }
            $with['on'][$categoryString] = '(' . implode(' OR ', $categoryWith) . ')';
        }
        
        return $with;
    }
}
