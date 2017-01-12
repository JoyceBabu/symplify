<?php declare(strict_types=1);

namespace Symplify\ModularDoctrineFilters\Adapter\Symfony\DependencyInjection\Extension;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ModularDoctrineFiltersExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $containerBuilder) : void
    {
        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../../../config'));
        $loader->load('services.neon');

        $enableFilterSubscriberDefinition = $containerBuilder->getDefinition('symplify.enable_filters_subscriber');
        $enableFilterSubscriberDefinition->addTag('kernel.event_subscriber');
    }
}
