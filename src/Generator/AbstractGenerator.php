<?php
/**
 * User: boshurik
 * Date: 2019-07-17
 * Time: 14:16
 */

namespace BoShurik\MapperBundle\Generator;

use BoShurik\MapperBundle\Generator\PhpParser\PrettyPrinter\PrettyPrinter;
use PhpParser\Node\Identifier;
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
        asort($classes);

        $uses = [];
        foreach ($classes as $key => $class) {
            $alias = null;
            if (is_string($key)) {
                $alias = new Identifier($key);
            }
            $uses[] = new Stmt\Use_([
                new Stmt\UseUse($class, $alias),
            ]);
        }

        return $uses;
    }
}