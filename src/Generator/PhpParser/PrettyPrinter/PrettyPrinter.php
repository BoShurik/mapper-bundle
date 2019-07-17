<?php
/**
 * User: boshurik
 * Date: 2019-07-17
 * Time: 15:21
 */

namespace BoShurik\MapperBundle\Generator\PhpParser\PrettyPrinter;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinter\Standard;

class PrettyPrinter extends Standard
{
    protected function pStmt_ClassMethod(Stmt\ClassMethod $node) {
        return $this->pModifiers($node->flags)
            . 'function ' . ($node->byRef ? '&' : '') . $node->name
            . '(' . $this->pCommaSeparated($node->params) . ')'
            . (null !== $node->returnType ? ': ' . $this->p($node->returnType) : '') // ' : ' => ': '
            . (null !== $node->stmts
                ? $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}'
                : ';')
            . \PHP_EOL; // \n
    }

    protected function pClassCommon(Stmt\Class_ $node, $afterClassToken) {
        return \PHP_EOL . parent::pClassCommon($node, $afterClassToken);
    }

    protected function pStmt_Property(Stmt\Property $node) {
        return parent::pStmt_Property($node) . \PHP_EOL;
    }

    /**
     * Pretty prints an array of nodes (statements) and indents them optionally.
     *
     * @param Node[] $nodes  Array of nodes
     * @param bool   $indent Whether to indent the printed nodes
     *
     * @return string Pretty printed statements
     */
    protected function pStmts(array $nodes, bool $indent = true) : string {
        if ($indent) {
            $this->indent();
        }

        $result = '';
        foreach ($nodes as $node) {
            $comments = $node->getComments();
            if ($comments) {
                $result .= $this->nl . $this->pComments($comments);
                if ($node instanceof Stmt\Nop) {
                    continue;
                }
            }

            if ($node instanceof Stmt\Return_ && $result !== '') { // New line before return statement
                $result .= \PHP_EOL;
            }

            $result .= $this->nl . $this->p($node);
        }

        if ($indent) {
            $this->outdent();
        }

        return $result;
    }
}