<?php
/**
 * User: boshurik
 * Date: 2019-07-17
 * Time: 13:57
 */

namespace BoShurik\MapperBundle\Generator;

use PhpParser\Comment\Doc;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;

class ModelGenerator extends AbstractGenerator
{
    public function generate(string $name, string $class): string
    {
        $className = new Name($name);

        $reflectionClass = new \ReflectionClass($class);

        $reflectionProperties = $reflectionClass->getProperties();

        $stmts = [];

        $properties = [];
        foreach ($reflectionProperties as $reflectionProperty) {
            $properties[] = new Stmt\Property(Stmt\Class_::MODIFIER_PUBLIC, [new Stmt\PropertyProperty($reflectionProperty->getName())], [
                'comments' => [
                    new Doc($this->getDocBlock('string')), // TODO: Guess type
                ]
            ]);
        }

        $stmts = array_merge($stmts, $properties);

        return $this->compile([
            new Stmt\Namespace_($className->slice(0, -1)),
            new Stmt\Class_($className->getLast(), [
                'flags' => Stmt\Class_::MODIFIER_FINAL,
                'stmts' => $stmts,
            ]),
        ]);
    }

    private function getDocBlock($type)
    {
        return <<< DOC
/**
     * @var $type|null
     */
DOC;
    }
}