<?php
/**
 * User: boshurik
 * Date: 2019-07-17
 * Time: 13:56
 */

namespace BoShurik\MapperBundle\Generator;

use BoShurik\Mapper\MapperInterface;
use BoShurik\MapperBundle\Mapper\Mapping\MappingInterface;
use BoShurik\MapperBundle\Mapper\Mapping\ReverseMappingInterface;
use PhpParser\Comment\Doc;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;

class MappingGenerator extends AbstractGenerator
{
    /**
     * @var Name
     */
    private $mapperClassName;

    public function __construct()
    {
        parent::__construct();

        $this->mapperClassName = new Name(MapperInterface::class);
    }

    public function generate(string $name, string $source, string $destination, bool $reverse): string
    {
        $className = new Name($name);
        $sourceClassName = new Name($source);
        $destinationClassName = new Name($destination);

        $interfaceClassName = $this->getInterface($reverse);

        $methodParams = [
            new Param(new Expr\Variable('source'), null, 'object'),
            new Param(new Expr\Variable('mapper'), null, $this->mapperClassName->getLast()),
            new Param(new Expr\Variable('context'), null, 'array'),
        ];

        $classStmts = [
            new Stmt\ClassMethod('getSource', [
                'flags' => Stmt\Class_::MODIFIER_PUBLIC | Stmt\Class_::MODIFIER_STATIC,
                'returnType' => 'string',
                'stmts' => [
                    new Stmt\Return_(new Expr\ClassConstFetch(new Name($sourceClassName->getLast()), 'class')),
                ],
            ], [
                'comments' => [
                    new Doc($this->getInheritDoc()),
                ],
            ]),
            new Stmt\ClassMethod('getDestination', [
                'flags' => Stmt\Class_::MODIFIER_PUBLIC | Stmt\Class_::MODIFIER_STATIC,
                'returnType' => 'string',
                'stmts' => [
                    new Stmt\Return_(new Expr\ClassConstFetch(new Name($destinationClassName->getLast()), 'class')),
                ],
            ], [
                'comments' => [
                    new Doc($this->getInheritDoc()),
                ],
            ]),
            new Stmt\ClassMethod('map', [
                'flags' => Stmt\Class_::MODIFIER_PUBLIC,
                'params' => $methodParams,
                'returnType' => 'object',
                'stmts' => $this->getMethodStatements($sourceClassName, $destinationClassName),
            ], [
                'comments' => [
                    new Doc($this->getDocBlock($sourceClassName->getLast(), $destinationClassName->getLast())),
                ],
            ])
        ];

        if ($reverse) {
            $classStmts[] = new Stmt\ClassMethod('reverseMap', [
                'flags' => Stmt\Class_::MODIFIER_PUBLIC,
                'params' => $methodParams,
                'returnType' => 'object',
                'stmts' => $this->getMethodStatements($destinationClassName, $sourceClassName),
            ], [
                'comments' => [
                    new Doc($this->getDocBlock($destinationClassName->getLast(), $sourceClassName->getLast())),
                ],
            ]);
        }

        $statements = [
            new Stmt\Namespace_($className->slice(0, -1)),
        ];

        $statements = array_merge($statements, $this->createUseStatements([
            $sourceClassName,
            $destinationClassName,
            $this->mapperClassName,
            $interfaceClassName,
        ]));

        $statements[] =  new Stmt\Class_($className->getLast(), [
            'implements' => [new Name($interfaceClassName->getLast())],
            'stmts' => $classStmts,
        ]);

        return $this->compile($statements);
    }

    private function getMethodStatements(Name $source, Name $destination): array
    {
        $statements = [];

        $sourceReflectionClass = new \ReflectionClass((string)$source);
        $destinationReflectionClass = new \ReflectionClass((string)$destination);

        $constructorArguments = [];

        if (count($sourceReflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC)) > count($destinationReflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC))) {
            if ($destinationReflectionClass->hasMethod('__construct')) {
                $destinationReflectionConstructor = $destinationReflectionClass->getMethod('__construct');
                foreach ($destinationReflectionConstructor->getParameters() as $constructorReflectionParameter) {
                    $constructorArguments[$constructorReflectionParameter->getName()] = new Arg(new Expr\PropertyFetch(new Expr\Variable('source'), $constructorReflectionParameter->getName()));
                }
            }

            $elseStatements = [];
            if (count($constructorArguments) > 0) {
                foreach (array_keys($constructorArguments) as $name) {
                    if (!$setter = $this->getSetterForProperty($destinationReflectionClass, $name)) {
                        continue;
                    }
                    $elseStatements[] = new Stmt\Expression(new Expr\MethodCall(
                        new Expr\Variable('destination'), $setter, [
                            new Arg(new Expr\PropertyFetch(new Expr\Variable('source'), $sourceReflectionClass->getProperty($name)->getName()))
                        ]
                    ));
                }
            }

            $statements[] = new Stmt\If_(
                new Expr\BooleanNot(
                    new Expr\Assign(
                        new Expr\Variable('destination'),
                        new Expr\BinaryOp\Coalesce(
                            new Expr\ArrayDimFetch(
                                new Expr\Variable('context'),
                                new Expr\ClassConstFetch(new Name($this->mapperClassName->getLast()), 'DESTINATION_CONTEXT')
                            ),
                            new Expr\ConstFetch(new Name('null'))
                        )
                    )
                ), [
                    'stmts' => [
                        new Stmt\Expression(
                            new Expr\Assign(new Expr\Variable('destination'), new Expr\New_(new Name($destination->getLast()), $constructorArguments))
                        ),
                    ],
                    'else' => new Stmt\Else_($elseStatements),
                ], [
                    'comments' => [
                        new Doc(sprintf('/** @var %s $destination */', $destination->getLast())),
                    ],
                ]
            );
        } else {
            $statements[] = new Stmt\Expression(
                new Expr\Assign(new Expr\Variable('destination'), new Expr\New_(new Name($destination->getLast())))
            );
        }

        foreach ($destinationReflectionClass->getProperties() as $destinationReflectionProperty) {
            if (isset($constructorArguments[$destinationReflectionProperty->getName()])) {
                continue;
            }
            if ($destinationReflectionProperty->isPublic()) {
                if (!$getter = $this->getGetterForProperty($sourceReflectionClass, $destinationReflectionProperty->getName())) {
                    continue;
                }
                $statements[] = new Stmt\Expression(
                    new Expr\Assign(
                        new Expr\PropertyFetch(new Expr\Variable('destination'), $destinationReflectionProperty->getName()),
                        new Expr\MethodCall(new Expr\Variable('source'), $getter)
                    )
                );
            } else {
                if (!$setter = $this->getSetterForProperty($destinationReflectionClass, $destinationReflectionProperty->getName())) {
                    continue;
                }
                if (!$sourceReflectionClass->hasProperty($destinationReflectionProperty->getName())) {
                    continue;
                }
                $statements[] = new Stmt\Expression(new Expr\MethodCall(
                    new Expr\Variable('destination'), $setter, [
                        new Arg(new Expr\PropertyFetch(new Expr\Variable('source'), $sourceReflectionClass->getProperty($destinationReflectionProperty->getName())->getName()))
                    ]
                ));
            }

        }

        $statements[] = new Stmt\Return_(new Expr\Variable('destination'));

        return $statements;
    }

    private function getGetterForProperty(\ReflectionClass $reflectionClass, $property): ?string
    {
        $getterName = sprintf('get%s', ucfirst($property));
        $issetName = sprintf('is%s', ucfirst($property));

        if ($reflectionClass->hasMethod($getterName)) {
            return $getterName;
        }
        if ($reflectionClass->hasMethod($issetName)) {
            return $issetName;
        }

        return null;
    }

    private function getSetterForProperty(\ReflectionClass $reflectionClass, $property): ?string
    {
        $setterName = sprintf('set%s', ucfirst($property));

        if ($reflectionClass->hasMethod($setterName)) {
            return $setterName;
        }

        return null;
    }

    private function getInterface(bool $reverse): Name
    {
        if ($reverse) {
            return new Name(ReverseMappingInterface::class);
        } else {
            return new Name(MappingInterface::class);
        }
    }

    private function getInheritDoc()
    {
        return <<< DOC
/**
     * @inheritDoc
     */
DOC;
    }

    private function getDocBlock($source, $destination)
    {
        return <<< DOC
/**
     * @param $source|object \$source
     * @param MapperInterface \$mapper
     * @param array \$context
     * @return $destination|object
     */
DOC;
    }
}