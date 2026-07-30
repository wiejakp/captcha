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
use wiejakp\captcha\Model\Challenge;
use wiejakp\captcha\Model\Equation;
use wiejakp\captcha\Tests\AbstractUnitTestCase;

#[CoversClass(Challenge::class)]
#[UsesClass(Equation::class)]
class ChallengeTest extends AbstractUnitTestCase
{
    public function testChallengeExposesConfiguredValues(): void
    {
        $challenge = new Challenge('2 + 2', '4');

        self::assertSame('2 + 2', $challenge->getChallenge());
        self::assertSame('4', $challenge->getSolution());
        self::assertNull($challenge->getEquation());
    }

    public function testSetEquationReturnsSelf(): void
    {
        $challenge = new Challenge('1 + 1', '2');
        $equation = new Equation(1, '+', 1);

        self::assertSame($challenge, $challenge->setEquation($equation));
        self::assertSame($equation, $challenge->getEquation());
    }

    public function testIsEqualTrimsComparedSolution(): void
    {
        $challenge = new Challenge('3 - 1', '2');

        self::assertTrue($challenge->isEqual(' 2 '));
        self::assertFalse($challenge->isEqual('5'));
    }
}
