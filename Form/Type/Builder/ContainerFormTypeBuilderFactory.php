<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\Type\Builder;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Symfony\Component\DependencyInjection\ContainerInterface;

use Oryzone\MediaStorage\Exception\InvalidArgumentException,
    Oryzone\MediaStorage\Exception\InvalidConfigurationException;

class ContainerFormTypeBuilderFactory implements FormTypeBuilderFactoryInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     * Array containing the aliases of the form type builders that can be instantiated
     *
     * E.g.
     *
     * <code>
     * array(
     *      'youtube' => 'oryzone_media_storage.form.type_builder.youtube',
     *      'vimeo'   => 'oryzone_media_storage.form.type_builder.vimeo'
     * )
     * </code>
     *
     * @var array $aliases
     */
    protected $aliases;

    protected $default;

    /**
     * Adds a service alias
     *
     * @param string $serviceName the name of the service in the DIC
     * @param string $alias       the alias
     */
    public function addAlias($serviceName, $alias)
    {
        $this->aliases[$alias] = $serviceName;
    }

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array                                                     $aliases
     * @param string                                                    $default
     */
    public function __construct(ContainerInterface $container, $aliases = array(), $default = 'default')
    {
        $this->container = $container;
        $this->aliases = $aliases;
        $this->default = $default;
    }

    /**
     * {@inheritDoc}
     */
    public function get($formTypeBuilderName)
    {
        if (!array_key_exists($formTypeBuilderName, $this->aliases)) {
            if(!array_key_exists($this->default, $this->aliases))
                throw new InvalidArgumentException(sprintf('Form type builder "%s" not found, tried to switch to default form type builder ("%s") but it was not found either', $formTypeBuilderName, $this->default));

            $formTypeBuilderName = $this->default;
        }

        $serviceName = $this->aliases[$formTypeBuilderName];

        if(!$this->container->has($serviceName))
            throw new InvalidArgumentException(sprintf('The service "%s" associated to the form type builder "%s" is not defined in the dependency injection container', $serviceName, $formTypeBuilderName));

        $service = $this->container->get($serviceName);
        if(!$service instanceof FormTypeBuilderInterface)
            throw new InvalidConfigurationException(sprintf('The service "%s" associated with the form type builder "%s" does not implement "Oryzone\Bundle\MediaStorageBundle\Form\Type\Builder\FormTypeBuilderInterface"', $serviceName, $formTypeBuilderName));

        return $service;
    }
}
