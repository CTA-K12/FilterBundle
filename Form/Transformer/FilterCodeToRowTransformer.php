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
        foreach ($rows as $cells) {
            $cellSolvents = array();
            foreach ($cells as $cell) {
                sort($cell->solvent, SORT_NUMERIC);
                $cellSolvent = json_encode($cell->solvent);
                $cellSolvents[] = $cell->associationId . $cellSolvent;
                $result = $this->entityManager
                    ->getRepository('MesdFilterBundle:FilterCell')
                    ->getBySolventAndAssociation(
                        $cellSolvent,
                        $cell->associationId
                    )->getQuery()
                    ->getResult()
                ;
                $n = count($result);
                if (0 === $n) {
                    $filterCell = new FilterCell();
                    $filterCell->setFilterAssociation(
                        $associations[$cell->associationId]
                    );
                    $filterCell->setSolvent($cell->solvent);
                    $this->entityManager->persist($filterCell);
                } elseif (1 === $n) {
                    $filterCell = $result[0];
                } else {
                    throw new DuplicateFilterCellException(
                        'there is a duplicate filter cell with solvent '
                        . $cellSolvent . ' and association '
                        . $cell->associationId
                    );
                }
            }
            print_r('<pre>');
            print_r('<hr />');
            print_r($cellSolvents);
            sort($cellSolvents);
            print_r($cellSolvents);
            print_r('<hr />');
            print_r('</pre>');
        }
        
        die;
        /*
        $filterRows = new ArrayCollection();
        $rows = explode(')v(', substr($solvent, 1, -1));
        var_dump($rows);
        foreach ($rows as $row) {
            $filterRow = new FilterRow();
            var_dump($row);
            $cells = explode(')^(', substr($row, 1, -1));
            var_dump($cells);
            $i = 0;
            $description = '';
            $n = count($cells);
            foreach ($cells as $cell) {
                if ('*' === $cell) {
                    $n--;
                }
            }
            foreach ($cells as $cell) {
                var_dump($cell);
                if ('*' !== $cell) {
                    $joins = explode('v', $cell);
                    var_dump($joins);
                    sort($joins, SORT_NUMERIC);
                    var_dump($joins);
                    $sortedCell = implode('v', $joins);
                    var_dump($sortedCell);
                    $filterCell = $this->entityManager
                        ->getRepository('MesdFilterBundle:FilterCell')
                        ->findOneBySolvent($sortedCell)
                    ;
                    if (null === $filterCell) {
                        $filterCell = new FilterCell();
                        $filterCell->setSolvent($sortedCell);
                    }

                    $filterRow->addFilterCell($filterCell);
                    if (0 < $i) {
                        $description .= '), ';
                        if (($i + 1) === $n) {
                            $description .= 'and (';
                        }
                    }
                    $description .= $filterCell->getDescription();
                    $i++;
                }
            }
            if (0 === $filterRow->getFilterCell()->count()) {
                throw new TransformationFailedException(sprintf(
                    'row "%s" does not have any cells!',
                    $row
                ));
            }
            $filterRow->setDescription('(' . $description . ')');
            $filterRows->add($filterRow);
            $this->entityManager->persist($filterRow);
        }
        die;

        return $filterRows;
        */
    }
}
