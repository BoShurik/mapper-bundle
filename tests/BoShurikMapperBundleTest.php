<?php
/**
 * User: boshurik
 * Date: 2019-03-27
 * Time: 15:22
 */

namespace BoShurik\MapperBundle\Tests;

use BoShurik\MapperBundle\BoShurikMapperBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class BoShurikMapperBundleTest extends TestCase
{
    public function testGetContainerExtension()
    {
        $bundle = new BoShurikMapperBundle();

        $extension = $bundle->getContainerExtension();
        $this->assertInstanceOf(ExtensionInterface::class, $extension);
        $this->assertSame('boshurik_mapper', $extension->getAlias());
    }
}