<?php

namespace Mesd\FilterBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class MesdFilterExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        // Store mesd_filter config parameters in container
        foreach ($config as $parameter => $value) {
            if (is_array($value)) {
                foreach ($value as $key => $val) {
                    if (is_array($val)) {
                        foreach ($val as $k => $v) {
                            $container->setParameter(
                                'mesd_filter.' . $parameter . '.' . $key . '.' . $k,
                                $v
                            );
                        }
                    } else {
                        $container->setParameter(
                            'mesd_filter.' . $parameter . '.' . $key,
                            $val
                        );
                    }
                }
            } else {
                $container->setParameter(
                    'mesd_filter.' . $parameter,
                    $value
                );
            }
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.yml');

        // Once the services definition are read, get your service and add a method call to setConfig()
        $serviceDefinition = $container->getDefinition('mesd_filter.filter_manager');
        $serviceDefinition->addMethodCall('setBypassRoles', array($config['filter']['bypass_roles']));
        $serviceDefinition->addMethodCall('setConfig', array($config['filter']['filters']));
    }
}
