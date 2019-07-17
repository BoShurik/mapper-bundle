<?php
/**
 * User: boshurik
 * Date: 2019-07-18
 * Time: 12:42
 */

namespace BoShurik\MapperBundle\Tests\Generator\Helper;

use BoShurik\MapperBundle\Generator\Helper\ClassPathResolver;
use PHPUnit\Framework\TestCase;

class ClassPathResolverTest extends TestCase
{
    /**
     * @dataProvider resolveDataProvider
     *
     * @param string $value
     * @param string|null $expected
     */
    public function testResolve(string $value, ?string $expected)
    {
        $resolver = new ClassPathResolver('/', [
            'App\\' => 'src/',
        ]);

        if ($expected) {
            $this->assertEquals($expected, $resolver->resolve($value));
        } else {
            $this->expectException(\RuntimeException::class);

            $resolver->resolve($value);
        }
    }

    public function resolveDataProvider(): array
    {
        return [
            ['App\Foo', '/src/Foo.php'],
            ['App\Foo\Bar', '/src/Foo/Bar.php'],
            ['Foo', null],
        ];
    }
}