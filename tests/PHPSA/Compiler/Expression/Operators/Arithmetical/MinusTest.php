<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Arithmetical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use Tests\PHPSA\Compiler\Expression\AbstractBinaryOp;

class MinusTest extends AbstractBinaryOp
{
    /**
     * Data provider for Minus {int} - {int} = {int}
     *
     * @return array
     */
    public function intToIntDataProvider()
    {
        return [
            [-1, -1, 0],
            [-1, 0, -1],
            [0, -1, 1],
            [-1, 2, -3],
            [2, -1, 3],
            [0, 0, 0],
            [0, 1, -1],
            [1, 0, 1],
            [1, 2, -1],
            [2, 1, 1],
            [25, 25, 0],
            [50, 25, 25],
            [50, -25, 75],
            [50, 50, 0],
            [50, -50, 100],
            [-50, -50, 0],
        ];
    }

    /**
     * Tests {int} - {int} = {int}
     *
     * @dataProvider intToIntDataProvider
     */
    public function testMinusIntFromInt($a, $b, $c)
    {
        $this->assertInternalType('int', $a);
        $this->assertInternalType('int', $b);
        $this->assertInternalType('int', $c);

        $baseExpression = new Node\Expr\BinaryOp\Minus(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\LNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Data provider for Minus {int} - {double} = {double} and {double} - {double} = {double}
     *
     * @return array
     */
    public function resultDoubleDataProvider()
    {
        return [
            [-1, -1.0, 0.0],
            [-1.0, -1.0, 0.0],
            [3, 1.5, 1.5],
            [4.0, 2.0, 2.0],
            [2, 3.5, -1.5],
            [2.5, 3.5, -1.0],
        ];
    }

    /**
     * Tests {int} - {double} = {double} and {double} - {double} = {double}
     *
     * @dataProvider resultDoubleDataProvider
     */
    public function testMinusResultDouble($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\Minus(
            new Node\Scalar\DNumber($a),
            new Node\Scalar\DNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::DOUBLE, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * @param Node\Scalar $a
     * @param Node\Scalar $b
     * @return Node\Expr\BinaryOp\Minus
     */
    protected function buildExpression($a, $b)
    {
        return new Node\Expr\BinaryOp\Minus($a, $b);
    }
}
