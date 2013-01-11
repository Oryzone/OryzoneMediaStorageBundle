<?php

namespace Oryzone\Bundle\MediaStorageBundle;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Symfony\Component\HttpKernel\Bundle\Bundle,
    Symfony\Component\DependencyInjection\ContainerBuilder;

use Oryzone\Bundle\MediaStorageBundle\DependencyInjection\Compiler\CdnCompilerPass,
    Oryzone\Bundle\MediaStorageBundle\DependencyInjection\Compiler\FormTypeBuilderCompilerPass,
    Oryzone\Bundle\MediaStorageBundle\DependencyInjection\Compiler\NamingStrategyCompilerPass,
    Oryzone\Bundle\MediaStorageBundle\DependencyInjection\Compiler\ProviderCompilerPass;

class OryzoneMediaStorageBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CdnCompilerPass());
        $container->addCompilerPass(new FormTypeBuilderCompilerPass());
        $container->addCompilerPass(new NamingStrategyCompilerPass());
        $container->addCompilerPass(new ProviderCompilerPass());
    }
}
