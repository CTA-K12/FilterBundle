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

print_r($queryBuilder->getQuery()->getDQL());
print_r('<hr />');
        $queryBuilder = $this->applySortedFilters($queryBuilder, $filtersByCategory);
print_r($queryBuilder->getQuery()->getDQL());
print_r('<hr />');

/*
        foreach ($filtersByCategory as $sortedFilters) {
            $details = array();
            foreach ($sortedFilters as $filter) {
                var_dump(get_class($filter));
                $solventWrappers = $filter->getSolventWrappers();
                $detail = $this->getDetail($solventWrappers, $alias);
                $details[] = $detail;
            }
            $detail = '(' . implode(' OR ', $details) . ')';
            $queryBuilder = $this->applyFilter($queryBuilder, $solventWrappers[0], $detail);
        }
*/
die;

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
        $rootAlias = $queryBuilder->getRootAlias();
        var_dump($rootAlias);
        
        $entityNames = array();
        
        foreach ($filtersByCategory as $filters) {
            $category = $filters[0]->getFilterCategory();
            $mainAlias = $category->getFilterEntity()->getDatabaseName();
            if ($rootAlias !== $mainAlias) {

                throw new MisappliedFilter('filter entity does not match querybuilder alias');
            }
            $categoryId = $category->getId();
            foreach ($category->getFilterAssociation() as $association) {
                var_dump($association->getName());
                $explodedTrail = explode('->', $association->getTrail());
                var_dump($explodedTrail);
                $alias = $mainAlias;
                foreach ($explodedTrail as $nextAlias) {
                    $newAlias = $nextAlias . $categoryId;
                    $queryBuilder->join($alias . '.' . $nextAlias, $newAlias);
                    $alias = $newAlias;
                }
            }
            foreach ($filters as $filter) {
                foreach ($filter->getFilterRow() as $row) {
                    var_dump($row->getDescription());
                    foreach ($row->getFilterCell() as $cell) {
                        var_dump($cell->getDescription());
                        $association = $cell->getFilterAssociation();
                        var_dump($association->getName());
                        var_dump($association->getTrailEntity()->getDatabaseName());
                    }
                }
            }
        }

        return $queryBuilder;
    }

/*
    protected function getDetail($solventWrappers, $alias) {
        $details = array();
        foreach ($solventWrappers as $solventWrapper) {
            $details[] = $solventWrapper->getDetails($alias);
        }
        $detail = '(' . implode(' OR ', $details) . ')';

        return $detail;
    }

    protected function applyFilter($queryBuilder, $solventWrapper, $detail)
    {
        $queryBuilder = $solventWrapper->applyToQueryBuilder($queryBuilder, $detail);

        return $queryBuilder;
    }

    public function getAsArray($filters, $metadataFactory)
    {
        $filterArray = array();
        foreach ($filters as $filter) {
            $solvents = $filter->getSolventWrappers();
            $solventArray = array();
            foreach ($solvents as $solvent) {
                $bunchArray = array();
                foreach ($solvent->getBunch() as $bunch) {
                    $entityArray = array();
                    foreach ($bunch->getEntity() as $entity) {
                        $metadata = $metadataFactory->getMetadataFor($entity->getName());
                        $joinArray = array();
                        foreach ($entity->getJoin() as $join) {
                            $associationMetadata = $metadata;
                            $associations = $join->getAssociation();
                            if ('id' === $associations[0]) {
                                $item = $this->objectManager->getRepository($entity->getName())->findOneById($join->getValue());
                            } else {
                                $length = count($associations);
                                for ($i = 0; $i < $length; $i++) {
                                     $targetEntity = $associationMetadata->getAssociationMapping($associations[$i])['targetEntity'];
                                     $associationMetadata = $metadataFactory->getMetadataFor($targetEntity);
                                }
                                $item = $this->objectManager->getRepository($associationMetadata->getName())->findOneById($join->getValue());
                            }
                            $joinArray[] = array(
                                'name' => $join->getName(),
                                'trail' => $join->getTrail(),
                                'item' => (string) $item,
                            );
                        }
                        $entityArray[] = array(
                            'name' => $entity->getName(),
                            'joins' => $joinArray,
                        );
                    }
                    $bunchArray[] = $entityArray;
                }
                $solventArray[] = $bunchArray;
            }

            $filterArray[] = array(
                'id'             => $filter->getId(),
                'filterCategory' => $filter->getFilterCategory(),
                'name'           => $filter->getName(),
                'solvent'        => $solventArray,
            );
        }

        return $filterArray;
    }

    public function getEntityLists($filterEntities, $metadataFactory)
    {
        $entityLists = array();
        foreach ($filterEntities as $entity) {
            $metadata = $metadataFactory->getMetadataFor($entity['entity']['name']);
            foreach ($entity['entity']['joins'] as $join) {
                if (!array_key_exists($join['name'], $entityLists)) {
                    if ('id' === $join['trail']) {
                        $entities = $this->objectManager->getRepository($entity['entity']['name'])->findAll();
                        $entityLists[$join['name']] = $entities;
                    } else {
                        $associations = explode('->', $join['trail']);
                        $associationMetadata = $metadata;
                        $length = count($associations);
                        for ($i = 0; $i < $length; $i++) {
                            $targetEntity = $associationMetadata->getAssociationMapping($associations[$i])['targetEntity'];
                            $associationMetadata = $metadataFactory->getMetadataFor($targetEntity);
                        }
                        $entities = $this->objectManager->getRepository($associationMetadata->getName())->findAll();
                        $entityLists[$join['name']] = $entities;
                    }
                }
            }
        }

        return $entityLists;
    }
    */
}
