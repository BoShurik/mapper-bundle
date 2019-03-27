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

        $this->assertInstanceOf(ExtensionInterface::class, $bundle->getContainerExtension());
    }
}