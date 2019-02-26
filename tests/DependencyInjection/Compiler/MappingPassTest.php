<?php
/**
 * User: boshurik
 * Date: 2019-02-26
 * Time: 23:08
 */

namespace BoShurik\MapperBundle\Tests\DependencyInjection\Compiler;

use BoShurik\Mapper\Mapping\MappingRegistry;
use BoShurik\MapperBundle\DependencyInjection\Compiler\MappingPass;
use BoShurik\MapperBundle\Tests\DependencyInjection\Compiler\Fixtures\Destination;
use BoShurik\MapperBundle\Tests\DependencyInjection\Compiler\Fixtures\Mapping;
use BoShurik\MapperBundle\Tests\DependencyInjection\Compiler\Fixtures\ReverseMapping;
use BoShurik\MapperBundle\Tests\DependencyInjection\Compiler\Fixtures\Source;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class MappingPassTest extends TestCase
{
    public function testRegisterMapping()
    {
        $container = $container = $this->buildContainer();

        $container
            ->register(Mapping::class)
            ->addTag(MappingPass::TAG)
        ;

        $container->compile();

        $registry = $container->get(MappingRegistry::class);

        $mapping = $registry->get(Source::class, Destination::class);

        $this->assertIsCallable($mapping);
        $this->assertInstanceOf(Mapping::class, $mapping[0]);
        $this->assertEquals('map', $mapping[1]);
    }

    public function testRegisterReverseMapping()
    {
        $container = $container = $this->buildContainer();

        $container
            ->register(ReverseMapping::class)
            ->addTag(MappingPass::TAG)
        ;

        $container->compile();

        $registry = $container->get(MappingRegistry::class);

        $mapping = $registry->get(Source::class, Destination::class);
        $this->assertIsCallable($mapping);
        $this->assertInstanceOf(ReverseMapping::class, $mapping[0]);
        $this->assertEquals('map', $mapping[1]);

        $reverseMapping = $registry->get(Destination::class, Source::class);
        $this->assertIsCallable($reverseMapping);
        $this->assertInstanceOf(ReverseMapping::class, $reverseMapping[0]);
        $this->assertEquals('reverseMap', $reverseMapping[1]);
    }

    public function testWrongInterfaceForTag()
    {
        $this->expectException(LogicException::class);

        $container = $this->buildContainer();

        $container
            ->register('service', '\stdClass')
            ->addTag(MappingPass::TAG)
        ;

        $container->compile();
    }

    /**
     * @return ContainerBuilder
     */
    private function buildContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new MappingPass());

        $container->register(MappingRegistry::class)->setPublic(true);

        return $container;
    }
}