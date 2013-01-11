<?php

namespace Oryzone\Bundle\MediaStorageBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class FormTypeBuilderCompilerPass implements CompilerPassInterface
{

    const FORM_TYPE_BUILDER_FACTORY_SERVICE = 'oryzone_media_storage.form.builder.container_factory';
    const FORM_TYPE_BUILDER_SERVICES_TAG = 'oryzone_media_storage_form_type_builder';

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(self::FORM_TYPE_BUILDER_FACTORY_SERVICE)) {
            throw new InvalidConfigurationException(sprintf('The form type builder factory service ("%s") is missing', self::FORM_TYPE_BUILDER_FACTORY_SERVICE));
        }

        $definition = $container->getDefinition(self::FORM_TYPE_BUILDER_FACTORY_SERVICE);

        foreach ($container->findTaggedServiceIds(self::FORM_TYPE_BUILDER_SERVICES_TAG) as $id => $attributes) {
            if(!isset($attributes[0]['alias']))
                throw new InvalidConfigurationException(sprintf('Service "%s" needs mandatory "alias" attribute for service tagged as "%s"', $id, self::FORM_TYPE_BUILDER_SERVICES_TAG));

            $definition->addMethodCall('addAlias', array($id, $attributes[0]['alias']));
        }
    }
}
