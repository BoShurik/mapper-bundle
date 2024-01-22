<?php
/**
 * User: boshurik
 * Date: 2019-02-26
 * Time: 21:40
 */

namespace BoShurik\MapperBundle\DependencyInjection;

use BoShurik\MapperBundle\DependencyInjection\Compiler\MappingPass;
use BoShurik\MapperBundle\Mapper\Mapping\MappingInterface;
use PhpParser\Parser;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Command\Command;
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

        if ($container->getParameter('kernel.environment') === 'dev'
            && interface_exists(Parser::class)
            && class_exists(Command::class)
        ) {
            $loader->load('generator.yaml');
        }

        $container
            ->registerForAutoconfiguration(MappingInterface::class)
            ->addTag(MappingPass::TAG)
        ;
    }

    /**
     * @inheritDoc
     */
    public function getAlias(): string
    {
        return 'boshurik_mapper';
    }
}