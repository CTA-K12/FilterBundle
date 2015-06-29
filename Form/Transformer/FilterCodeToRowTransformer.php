<?php

namespace Mesd\FilterBundle\Form\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

use Mesd\FilterBundle\Entity\FilterRow;

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
    public function reverseTransform($solvent)
    {
        $filterRows = new ArrayCollection();
        $rows = explode('v', $solvent);
        foreach ($rows as $row) {
            $filterRow = new FilterRow();
            $cells = explode('^', $row);
            $i = 0;
            $description = '';
            $n = count($cells);
            foreach ($cells as $cell) {
                if ('*' === $cell) {
                    $n--;
                }
            }
            foreach ($cells as $cell) {
                if ('*' !== $cell) {
                    $filterCell = $this->entityManager
                        ->getRepository('MesdFilterBundle:FilterCell')
                        ->find($cell)
                    ;
                    if (null === $filterCell) {
                        throw new TransformationFailedException(sprintf(
                            'An cell with id "%s" does not exist!',
                            $cell
                        ));
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

        return $filterRows;
    }
}
