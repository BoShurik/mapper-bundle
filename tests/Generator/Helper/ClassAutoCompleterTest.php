<?php
/**
 * User: boshurik
 * Date: 2019-07-18
 * Time: 13:51
 */

namespace BoShurik\MapperBundle\Tests\Generator\Helper;

use BoShurik\MapperBundle\Generator\Helper\ClassAutoCompleter;
use PHPUnit\Framework\TestCase;

class ClassAutoCompleterTest extends TestCase
{
    /**
     * @dataProvider invokeDataProvider
     *
     * @param string $input
     * @param array $expected
     */
    public function testInvoke(string $input, array $expected)
    {
        $autocompleter = new ClassAutoCompleter(__DIR__ . '/../../..', [
            'BoShurik\\MapperBundle\\Tests\\' => 'tests/',
        ]);

        $this->assertResult($expected, $autocompleter($input));
    }

    public function invokeDataProvider()
    {
        return [
            ['', [
                'BoShurik\\MapperBundle\\Tests\\',
            ]],
            ['BoShurik\\MapperBundle\\Tests\\', [
                'BoShurik\\MapperBundle\\Tests\\DependencyInjection\\',
                'BoShurik\\MapperBundle\\Tests\\Generator\\',
                'BoShurik\\MapperBundle\\Tests\\BoShurikMapperBundleTest',
            ]],
            ['BoShurik\\MapperBundle\\Tests\\DependencyInjection\\', [
                'BoShurik\\MapperBundle\\Tests\\DependencyInjection\\Compiler\\'
            ]],
            ['BoShurik\\MapperBundle\\Tests\\DependencyInjection\\Compiler\\', [
                'BoShurik\\MapperBundle\\Tests\\DependencyInjection\\Compiler\\Fixtures\\',
                'BoShurik\\MapperBundle\\Tests\\DependencyInjection\\Compiler\\MappingPassTest'
            ]],
            ['BoShurik\\MapperBundle\\Tests\\DependencyInjection\\Compiler\\Fixtures\\', [
                'BoShurik\\MapperBundle\\Tests\\DependencyInjection\\Compiler\\Fixtures\\Destination',
                'BoShurik\\MapperBundle\\Tests\\DependencyInjection\\Compiler\\Fixtures\\Mapping',
                'BoShurik\\MapperBundle\\Tests\\DependencyInjection\\Compiler\\Fixtures\\ReverseMapping',
                'BoShurik\\MapperBundle\\Tests\\DependencyInjection\\Compiler\\Fixtures\\Source',
            ]],
        ];
    }

    public function assertResult(array $expected, array $result)
    {
        $this->assertCount(count($expected), $result);

        foreach ($expected as $value) {
            $this->assertNotFalse(
                array_search($value, $result),
                sprintf('There is no %s value in %s', $value, implode(', ', $result))
            );
        }
    }
}