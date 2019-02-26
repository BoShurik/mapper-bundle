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
    /**
     * @return string
     */
    public static function getSource(): string;

    /**
     * @return string
     */
    public static function getDestination(): string;

    /**
     * @param object $source
     * @param MapperInterface $mapper
     * @param array $context
     * @return mixed
     */
    public function map(object $source, MapperInterface $mapper, array $context): object;
}