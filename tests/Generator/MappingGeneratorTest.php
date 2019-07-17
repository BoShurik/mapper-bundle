<?php
/**
 * User: boshurik
 * Date: 2019-07-17
 * Time: 14:00
 */

namespace BoShurik\MapperBundle\Tests\Generator;

use BoShurik\MapperBundle\Generator\MappingGenerator;
use BoShurik\MapperBundle\Tests\Generator\Fixtures\Post;
use BoShurik\MapperBundle\Tests\Generator\Fixtures\PostModel;
use BoShurik\MapperBundle\Tests\Generator\Fixtures\PostModelMapping;
use BoShurik\MapperBundle\Tests\Generator\Fixtures\PostModelReverseMapping;
use PHPUnit\Framework\TestCase;

class MappingGeneratorTest extends TestCase
{
    public function testGenerate()
    {
        $generator = new MappingGenerator();

        $expected = file_get_contents(__DIR__ . '/Fixtures/PostModelMapping.php');

        $this->assertEquals($expected, $generator->generate(PostModelMapping::class, PostModel::class, Post::class, false));
    }

    public function testGenerateReverse()
    {
        $generator = new MappingGenerator();

        $expected = file_get_contents(__DIR__ . '/Fixtures/PostModelReverseMapping.php');

        $this->assertEquals($expected, $generator->generate(PostModelReverseMapping::class, PostModel::class, Post::class, true));
    }
}