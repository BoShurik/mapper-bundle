<?php
/**
 * User: boshurik
 * Date: 2019-02-26
 * Time: 21:40
 */

namespace BoShurik\MapperBundle\DependencyInjection;

use BoShurik\MapperBundle\DependencyInjection\Compiler\MappingPass;
use BoShurik\MapperBundle\Mapper\Mapping\MappingInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class BoShurikMapperExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');

        $container
            ->registerForAutoconfiguration(MappingInterface::class)
            ->addTag(MappingPass::TAG)
        ;
    }
}