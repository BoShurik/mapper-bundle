<?php
/**
 * User: boshurik
 * Date: 2019-02-26
 * Time: 22:49
 */

namespace BoShurik\MapperBundle\DependencyInjection\Compiler;

use BoShurik\Mapper\Mapping\MappingRegistry;
use BoShurik\MapperBundle\Mapper\Mapping\MappingInterface;
use BoShurik\MapperBundle\Mapper\Mapping\ReverseMappingInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class MappingPass implements CompilerPassInterface
{
    public const TAG = 'boshurik_mapper.mapping';

    use PriorityTaggedServiceTrait;

    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $mappingRegistry = $container->getDefinition(MappingRegistry::class);

        $mappings = $this->findAndSortTaggedServices(self::TAG, $container);
        foreach ($mappings as $mapping) {
            $definition = $container->getDefinition($mapping);
            $class = $definition->getClass();
            $interfaces = class_implements($class);
            if (!isset($interfaces[MappingInterface::class])) {
                throw new LogicException(sprintf(
                    'Can\'t apply tag "%s" to %s class. It must implement %s interface',
                    self::TAG,
                    $class,
                    MappingInterface::class
                ));
            }

            $source = call_user_func([$class, 'getSource']);
            $destination = call_user_func([$class, 'getDestination']);

            $mappingRegistry->addMethodCall('add', [
                $source,
                $destination,
                [$mapping, 'map'],
            ]);

            if (isset($interfaces[ReverseMappingInterface::class])) {
                $mappingRegistry->addMethodCall('add', [
                    $destination,
                    $source,
                    [$mapping, 'reverseMap'],
                ]);
            }
        }
    }
}