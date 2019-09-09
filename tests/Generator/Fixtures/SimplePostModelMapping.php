<?php

namespace BoShurik\MapperBundle\Tests\Generator\Fixtures;

use BoShurik\Mapper\MapperInterface;
use BoShurik\MapperBundle\Mapper\Mapping\ReverseMappingInterface;
use BoShurik\MapperBundle\Tests\Generator\Fixtures\Post;
use BoShurik\MapperBundle\Tests\Generator\Fixtures\SimplePostModel;

class SimplePostModelMapping implements ReverseMappingInterface
{
    /**
     * @inheritDoc
     */
    public static function getSource(): string
    {
        return SimplePostModel::class;
    }

    /**
     * @inheritDoc
     */
    public static function getDestination(): string
    {
        return Post::class;
    }

    /**
     * @param SimplePostModel|object $source
     * @param MapperInterface $mapper
     * @param array $context
     * @return Post|object
     */
    public function map(object $source, MapperInterface $mapper, array $context): object
    {
        /** @var Post $destination */
        if (!($destination = $context[MapperInterface::DESTINATION_CONTEXT] ?? null)) {
            $destination = new Post($source->name);
        } else {
            $destination->setName($source->name);
        }

        return $destination;
    }

    /**
     * @param Post|object $source
     * @param MapperInterface $mapper
     * @param array $context
     * @return SimplePostModel|object
     */
    public function reverseMap(object $source, MapperInterface $mapper, array $context): object
    {
        $destination = new SimplePostModel();
        $destination->id = $source->getId();
        $destination->name = $source->getName();

        return $destination;
    }

}