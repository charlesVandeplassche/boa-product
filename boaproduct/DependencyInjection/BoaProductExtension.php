<?php

namespace App\Boa\ProductBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class BoaProductExtension extends Extension
{
    /**
     * @var array
     */
    private static $doctrineDrivers = array(
        'orm' => array(
            'registry' => 'doctrine',
            'tag' => 'doctrine.event_subscriber',
        )
    );

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('doctrine.yaml');

        // alias | boa_product.doctrine_registry: @doctrine
        $container->setAlias('boa_product.doctrine_registry', new Alias(self::$doctrineDrivers['orm']['registry'], false));

        $container->setParameter($this->getAlias().'.backend_type_orm', true);

        if (isset(self::$doctrineDrivers['orm'])) {
            $definition = $container->getDefinition('boa_product.object_manager');

            $definition->setFactory(array(new Reference('boa_product.doctrine_registry'), 'getManager'));
        }

        // alias | boa_product.product_manager: @boa_product.product_manager.default
        $container->setAlias('boa_product.product_manager', new Alias($config['service']['product_manager'], true));

        $container->setAlias('App\Boa\ProductBundle\Model\ProductManagerInterface', new Alias('boa_product.product_manager', true));

        $this->remapParametersNamespaces($config, $container, array(
            '' => array(
                'db_driver' => 'boa_product.storage',
                'model_manager_name' => 'boa_product.model_manager_name',
                'product_class' => 'boa_product.model.product.class',
            ),
        ));

        $this->loadCreate($config['create'], $container, $loader);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $namespaces
     */
    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!array_key_exists($ns, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    $container->setParameter(sprintf($map, $name), $value);
                }
            }
        }
    }

    /*
    * @param array            $config
    * @param ContainerBuilder $container
    * @param array            $map
    */
    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (array_key_exists($name, $config)) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param YamlFileLoader    $loader
     */
    private function loadCreate(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('creation.yaml');

        $this->remapParametersNamespaces($config, $container, array(
            'form' => 'boa_product.create.form.%s',
        ));
    }
}