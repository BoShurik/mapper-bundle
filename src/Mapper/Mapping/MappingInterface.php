<?php
/**
 * User: boshurik
 * Date: 2019-02-26
 * Time: 21:42
 */

namespace BoShurik\MapperBundle\Mapper\Mapping;

use BoShurik\Mapper\MapperInterface;

interface MappingInterface
{
    public static function getSource(): string;

    public static function getDestination(): string;

    public function map(object $source, MapperInterface $mapper, array $context): object;
}