<?php

namespace Myowncode\TurboSmsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class TurboSmsExtension
 *
 * @package \Myowncode\TurboSmsBundle\DependencyInjection
 */
class MyowncodeTurbosmsExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('myowncode_turbosms_config.login', $config['login']);
        $container->setParameter('myowncode_turbosms_config.password', $config['password']);
        $container->setParameter('myowncode_turbosms_config.sender', $config['sender']);
        $container->setParameter('myowncode_turbosms_config.debug', $config['debug']);
        $container->setParameter('myowncode_turbosms_config.save_to_db', $config['save_to_db']);
        $container->setParameter('myowncode_turbosms_config.wsdl', $config['wsdl']);
    }
}