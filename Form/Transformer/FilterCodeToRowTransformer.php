<?php

namespace Mesd\FilterBundle\Form\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

use Mesd\FilterBundle\Entity\FilterRow;
use Mesd\FilterBundle\Entity\FilterCell;
use Mesd\FilterBundle\Exception\DuplicateFilterCellException;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FilterCodeToRowTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an ArrayCollection ($filterRow) to a string.
     *
     * @param  ArrayCollection $filterRow
     * @return string
     */
    public function transform($filterRow)
    {
        if ($filterRow->isEmpty()) {

            return '';
        }
        
        foreach ($filterRow as $row) {
            var_dump($row->getSolvent());
        }
        die();

        return '';
    }

    /**
     * Transforms a string ($solvent) to an array.
     *
     * @param  string $solvent
     *
     * @return ArrayCollection
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($filterSolvent)
    {
        $rows = json_decode($filterSolvent);
        $associations = array();
        foreach ($rows as $cells) {
            foreach ($cells as $cell) {
                if (!array_key_exists($cell->associationId, $associations)) {
                    $association = $this->entityManager
                        ->getRepository('MesdFilterBundle:FilterAssociation')
                        ->find($cell->associationId)
                    ;
                    $associations[$cell->associationId] = $association;
                }
            }
        }
        $filterRows = new ArrayCollection();
        foreach ($rows as $cells) {
            sort($cells);
            $result = $this->entityManager
                ->getRepository('MesdFilterBundle:FilterRow')
                ->getBySolvent(
                    json_encode($cells)
                )->getQuery()
                ->getResult()
            ;
            $resultCount = count($result);
            if (0 === $resultCount) {
                $filterRow = new FilterRow();
                $filterRow->setSolvent($cells);
                $this->entityManager->persist($filterRow);
            } elseif (1 === $resultCount) {
                $filterRow = $result[0];
            } else {
                throw new DuplicateFilterCellException(
                    'there is a duplicate filter row with solvent '
                    . $cells
                );
            }
            $rowDescription = '';
            $rowDescriptionIndex = 0;
            $rowDescriptionCount = 0;
            foreach ($cells as $cell) {
                if (-1 < $cell->solvent[0]) {
                    $rowDescriptionCount++;
                }
            }
            foreach ($cells as $cell) {
                if (-1 < $cell->solvent[0]) {
                    sort($cell->solvent);
                    $result = $this->entityManager
                        ->getRepository('MesdFilterBundle:FilterCell')
                        ->getBySolventAndAssociation(
                            json_encode($cell->solvent),
                            $cell->associationId
                        )->getQuery()
                        ->getResult()
                    ;
                    $resultCount = count($result);
                    if (0 === $resultCount) {
                        $filterCell = new FilterCell();
                        $association = $associations[$cell->associationId];
                        $filterCell->setFilterAssociation($association);
                        $filterCell->setSolvent($cell->solvent);
                        $trailEntity = $association->getTrailEntity();
                        $results = $this->entityManager
                            ->getRepository($trailEntity->getName())
                            ->findById($cell->solvent)
                        ;
                        $cellDescription = '';
                        $cellDescriptionIndex = 0;
                        $cellDescriptionCount = count($results);
                        foreach ($results as $result) {
                            if (0 < $cellDescriptionIndex) {
                                $cellDescription .= ', ';
                                if (($cellDescriptionCount - 1) === $cellDescriptionIndex) {
                                    $cellDescription .= 'or ';
                                }
                            }
                            $cellDescription .= $result->__toString();
                            $cellDescriptionIndex++;
                        }
                        $filterCell->setDescription($cellDescription);
                        $this->entityManager->persist($filterCell);
                    } elseif (1 === $resultCount) {
                        $filterCell = $result[0];
                    } else {
                        throw new DuplicateFilterCellException(
                            'there is a duplicate filter cell with solvent '
                            . $cell->solvent . ' and association '
                            . $cell->associationId
                        );
                    }
                    $filterRow->addFilterCell($filterCell);
                    if (0 < $rowDescriptionIndex) {
                        $rowDescription .= ', ';
                        if (($rowDescriptionCount - 1) === $rowDescriptionIndex) {
                            $rowDescription .= 'and ';
                        }
                    }
                    $rowDescription .= '(' . $cellDescription . ')';
                    $rowDescriptionIndex++;
                }
            }
            $filterRow->setDescription($rowDescription);
            $filterRows->add($filterRow);
        }
        
        return $filterRows;
    }
}
