<?php

namespace Mesd\FilterBundle\Form\Factory;

use Doctrine\Common\Persistence\ManagerRegistry;

use Mesd\FilterBundle\Form\Transformer\FilterCodeToRowTransformer;

class FilterCodeToRowTransformerFactory
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function create($objectManager)
    {
        return new FilterCodeToRowTransformer($this->registry->getManager($objectManager));
    }
}
