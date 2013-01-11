<?php

/*
 * Copyright 2012 Oryzone, developed by Luciano Mammino <lmammino@oryzone.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Oryzone\Bundle\MediaStorageBundle;

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