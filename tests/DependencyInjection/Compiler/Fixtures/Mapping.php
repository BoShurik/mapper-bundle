<?php
/**
 * User: boshurik
 * Date: 2019-02-26
 * Time: 23:18
 */

namespace BoShurik\MapperBundle\Tests\DependencyInjection\Compiler\Fixtures;

use BoShurik\Mapper\MapperInterface;
use BoShurik\MapperBundle\Mapper\Mapping\MappingInterface;

class Mapping implements MappingInterface
{
    /**
     * @inheritDoc
     */
    public static function getSource(): string
    {
        return Source::class;
    }

    /**
     * @inheritDoc
     */
    public static function getDestination(): string
    {
        return Destination::class;
    }

    /**
     * @inheritDoc
     */
    public function map(object $source, MapperInterface $mapper, array $context): object
    {
        return new Destination();
    }
}