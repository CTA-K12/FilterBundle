<?php

namespace Mesd\FilterBundle\Form;

use Doctrine\ORM\EntityRepository;

use Mesd\FilterBundle\Form\Factory\FilterCodeToRowTransformerFactory;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilterType extends AbstractType
{
    /**
     * @var FilterCodeToRowTransformerFactory
     */
    private $factory;

    public function __construct(FilterCodeToRowTransformerFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add(
                'filterEntity',
                null,
                array(
                    'required' => true,
                    'empty_value' => '',
                    'query_builder' => function (EntityRepository $entityRepository) {
                        return $entityRepository->getCategories();
                    },
                )
            )
        ;
        $transformer = $this->factory->create($options['om']);
        $builder
            ->add(
                $builder->create(
                    'filterRow',
                    'textarea',
                    array(
                        'required' => true,
                    )
                )
                ->addModelTransformer($transformer)
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'Mesd\FilterBundle\Entity\Filter'
            ))
            ->setRequired(array('om'))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mesd_filterbundle_filter';
    }
}
