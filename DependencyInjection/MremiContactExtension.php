<?php

namespace Mremi\ContactBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MremiContactExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('contact.xml');
        $loader->load('form.xml');
//        $loader->load('listeners.xml');
//        $loader->load('mailer.xml');

        $this->configureContactManager($container, $config);
        $this->configureForm($container, $config);
    }

    /**
     * Configures the contact manager service
     *
     * @param ContainerBuilder $container A container builder instance
     * @param array            $config    An array of configuration
     */
    private function configureContactManager(ContainerBuilder $container, array $config)
    {
        $definition = $container->getDefinition('mremi_contact.contact_manager');
        $definition->replaceArgument(0, $config['contact_class']);
    }

    /**
     * Configures the form services
     *
     * @param ContainerBuilder $container A container builder instance
     * @param array            $config    An array of configuration
     */
    private function configureForm(ContainerBuilder $container, array $config)
    {
        $definition = $container->getDefinition('mremi_contact.form_factory');
        $definition->replaceArgument(1, $config['form']['type']);
        $definition->replaceArgument(2, $config['form']['name']);
        $definition->replaceArgument(3, $config['form']['validation_groups']);

        $definition = $container->getDefinition('mremi_contact.contact_form_type');
        $definition->replaceArgument(0, $config['contact_class']);
    }
}