<?php

namespace Mesd\FilterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilterType extends AbstractType
{
    private $filterClassName;
    private $filterCategoryClassName;

    public function __construct($filterClassName, $filterCategoryClassName)
    {
        $this->filterClassName = $filterClassName;
        $this->filterCategoryClassName = $filterCategoryClassName;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager = $options['entityManager'];
        $builder
            ->add(
                'filterCategory',
                'entity',
                array(
                    'class' => $this->filterCategoryClassName,
                    'label' => 'Category',
                    'required' => true,
                    'empty_value' => '',
                )
            )
            ->add('description')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->filterClassName
        ));

        $resolver->setRequired(array('entityManager'));
    }

    public function getName()
    {
        return 'mesd_filter_filter';
    }
}