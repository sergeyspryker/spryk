<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk;

use SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\SprykConfigurationExtender;
use SprykerSdk\Spryk\Model\Spryk\Configuration\Extender\SprykConfigurationExtenderPluginInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
    }

    protected function configureContainer(ContainerConfigurator $configurator): void
    {
        $function = require_once __DIR__ . '/../config/services.php';
        $function($configurator);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AutowireArrayParameterCompilerPass());
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $configExtenderChain = $container->getDefinition(SprykConfigurationExtender::class);
        $configExtenders = $container->findTaggedServiceIds(SprykConfigurationExtenderPluginInterface::class);

        foreach ($configExtenders as $serviceId => $attributes) {
            foreach ($attributes as $attr) {
                $configExtenderChain->addMethodCall('addConfigExtender', [
                    new Definition($serviceId),
                ]);
            }
        }
    }
}
