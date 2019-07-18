<?php
/**
 * User: boshurik
 * Date: 2019-07-17
 * Time: 14:16
 */

namespace BoShurik\MapperBundle\Generator;

use BoShurik\MapperBundle\Generator\PhpParser\PrettyPrinter\PrettyPrinter;
use PhpParser\Node\Stmt;

abstract class AbstractGenerator
{
    /**
     * @var PrettyPrinter
     */
    protected $printer;

    public function __construct()
    {
        $this->printer = new PrettyPrinter();
    }

    protected function compile(array $stmts): string
    {
        return "<?php\n\n" . $this->printer->prettyPrint($stmts);
    }

    protected function createUseStatements(array $classes): array
    {
        sort($classes);

        $uses = [];
        foreach ($classes as $class) {
            $uses[] = new Stmt\Use_([
                new Stmt\UseUse($class),
            ]);
        }

        return $uses;
    }
}