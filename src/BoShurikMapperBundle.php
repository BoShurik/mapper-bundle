<?php
/**
 * User: boshurik
 * Date: 2019-02-26
 * Time: 21:39
 */

namespace BoShurik\MapperBundle;

use BoShurik\MapperBundle\DependencyInjection\BoShurikMapperExtension;
use BoShurik\MapperBundle\DependencyInjection\Compiler\MappingPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BoShurikMapperBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MappingPass());
    }

    /**
     * @inheritDoc
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new BoShurikMapperExtension();
        }

        return $this->extension;
    }
}