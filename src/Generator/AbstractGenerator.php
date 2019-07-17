<?php
/**
 * User: boshurik
 * Date: 2019-07-17
 * Time: 14:16
 */

namespace BoShurik\MapperBundle\Generator;

use BoShurik\MapperBundle\Generator\PhpParser\PrettyPrinter\PrettyPrinter;

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
}