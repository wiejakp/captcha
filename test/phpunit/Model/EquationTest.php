<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha\Tests\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use wiejakp\captcha\Exception\InvalidEquationException;
use wiejakp\captcha\Model\Equation;
use wiejakp\captcha\Tests\AbstractUnitTestCase;

#[CoversClass(Equation::class)]
#[UsesClass(InvalidEquationException::class)]
class EquationTest extends AbstractUnitTestCase
{
    public function testEquationAddsValues(): void
    {
        $equation = new Equation(6, '+', 4);

        self::assertSame('10', $equation->getSolution());
    }

    public function testEquationExposesComputedValues(): void
    {
        $equation = new Equation(6, '-', 4);

        self::assertSame(6, $equation->getLeft());
        self::assertSame('-', $equation->getSign());
        self::assertSame(4, $equation->getRight());
        self::assertSame('6 - 4', $equation->getEquation());
        self::assertSame('2', $equation->getSolution());
    }

    public function testEquationWrapsInvalidExpressions(): void
    {
        $this->expectException(InvalidEquationException::class);
        $this->expectExceptionMessage('Invalid Equation: 1 invalid 2');

        new Equation(1, 'invalid', 2);
    }

    public function testEquationRejectsUnsupportedExecutableOperators(): void
    {
        $this->expectException(InvalidEquationException::class);
        $this->expectExceptionMessage('Invalid Equation: 8 / 2');

        new Equation(8, '/', 2);
    }
}
