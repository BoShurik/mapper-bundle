<?php
/**
 * User: boshurik
 * Date: 2019-02-26
 * Time: 21:42
 */

namespace BoShurik\MapperBundle\Mapper\Mapping;

use BoShurik\Mapper\MapperInterface;

interface ReverseMappingInterface extends MappingInterface
{
    public function reverseMap(object $source, MapperInterface $mapper, array $context): object;
}