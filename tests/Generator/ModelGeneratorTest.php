<?php
/**
 * User: boshurik
 * Date: 2019-07-17
 * Time: 14:00
 */

namespace BoShurik\MapperBundle\Tests\Generator;

use BoShurik\MapperBundle\Generator\ModelGenerator;
use BoShurik\MapperBundle\Tests\Generator\Fixtures\Post;
use BoShurik\MapperBundle\Tests\Generator\Fixtures\PostModel;
use PHPUnit\Framework\TestCase;

class ModelGeneratorTest extends TestCase
{
    public function testGenerate()
    {
        $generator = new ModelGenerator();

        $expected = file_get_contents(__DIR__ . '/Fixtures/PostModel.php');

        $this->assertEquals($expected, $generator->generate(PostModel::class, Post::class));
    }
}