<?php

namespace Mesd\FilterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OneUserManyFiltersType extends AbstractType
{
    private $userClassName;

    public function __construct($userClassName)
    {
        $this->userClassName = $userClassName;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('mesdFilter');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->userClassName
        ));
    }

    public function getName()
    {
        return 'mesd_filter_one_user_many_filters';
    }
}
