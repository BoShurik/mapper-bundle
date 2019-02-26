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
    /**
     * @param object $source
     * @param MapperInterface $mapper
     * @param array $context
     * @return object
     */
    public function reverseMap(object $source, MapperInterface $mapper, array $context): object;
}