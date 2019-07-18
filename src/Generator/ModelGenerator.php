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
    public function generate(string $name, string $class, bool $constraints = true): string
    {
        $className = new Name($name);

        $reflectionClass = new \ReflectionClass($class);

        $reflectionProperties = $reflectionClass->getProperties();

        $stmts = [];

        $properties = [];
        foreach ($reflectionProperties as $reflectionProperty) {
            $properties[] = new Stmt\Property(Stmt\Class_::MODIFIER_PUBLIC, [new Stmt\PropertyProperty($reflectionProperty->getName())], [
                'comments' => [
                    new Doc($this->getDocBlock('string', $constraints)), // TODO: Guess type
                ]
            ]);
        }

        $stmts = array_merge($stmts, $properties);

        $statements = [
            new Stmt\Namespace_($className->slice(0, -1)),
        ];

        if ($constraints) {
            $statements = array_merge($statements, $this->createUseStatements([
                'Assert' => new Name('Symfony\Component\Validator\Constraints'),
            ]));
        }

        $statements[] = new Stmt\Class_($className->getLast(), [
            'flags' => Stmt\Class_::MODIFIER_FINAL,
            'stmts' => $stmts,
        ]);

        return $this->compile($statements);
    }

    private function getDocBlock(string $type, bool $addConstraint)
    {
        if ($addConstraint) {
            $constraint = "\n     *\n     * @Assert\NotBlank()\n";
        } else {
            $constraint = '';
        }

        return <<< DOC
/**
     * @var $type|null$constraint
     */
DOC;
    }
}