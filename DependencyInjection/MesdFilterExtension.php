<?php

namespace Mesd\FilterBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class MesdFilterExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        // Store mesd_filter config parameters in container
        foreach( $config as $parameter => $value ) {

            if (is_array($value)) {
                foreach ($value as $key => $val) {
                    if (is_array($val)) {
                        foreach ($val as $k => $v) {
                            $container->setParameter(
                                'mesd_filter.' . $parameter . '.' . $key . '.' . $k,
                                $v
                            );
                        }
                    }
                    else {
                        $container->setParameter(
                            'mesd_filter.' . $parameter . '.' . $key,
                            $val
                        );
                    }
                }
            }
            else {
                $container->setParameter(
                    'mesd_filter.' . $parameter,
                    $value
                );
            }
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('FilterManagerService.yml');
        $container->setParameter('mesd_filter.filter_class_placeholder', $container->getParameter('mesd_filter.filter_class'));
        $container->setParameter('mesd_filter.filter_category_class_placeholder', $container->getParameter('mesd_filter.filter_category_class'));

        //Set the filters enabled on the user metadata listener to true
        $userMetadataListener->addMethodCall('setFiltersEnabled', array(true));

        // Once the services definition are read, get your service and add a method call to setConfig()
        $serviceDefinition = $container->getDefinition( 'mesd_filter.filter_manager' );
        $serviceDefinition->addMethodCall( 'setBypassRoles', array( $config[ 'filter' ][ 'bypass_roles' ] ) );
        $serviceDefinition->addMethodCall( 'setConfig', array( $config[ 'filter' ][ 'filters' ] ) );
    }
}