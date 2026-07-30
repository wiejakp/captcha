<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha\Tests\Factory;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use ReflectionMethod;
use wiejakp\captcha\Factory\ChallengeFactory;
use wiejakp\captcha\Model\Challenge;
use wiejakp\captcha\Model\Equation;
use wiejakp\captcha\Tests\AbstractUnitTestCase;

#[CoversClass(ChallengeFactory::class)]
#[UsesClass(Challenge::class)]
#[UsesClass(Equation::class)]
class ChallengeFactoryTest extends AbstractUnitTestCase
{
    public function testConstructorInitializesLocaleRangeSignsAndNumbers(): void
    {
        $factory = new ChallengeFactory('en', array(1, 2));

        self::assertSame('en', $factory->getValueLocale());
        self::assertSame(array(1, 2), $factory->getValueRange());
        self::assertSame(array('+' => 'plus', '-' => 'minus'), $factory->getValueSigns());
        self::assertSame('one', $factory->getValueNumbers()[1]);
        self::assertSame('two', $factory->getValueNumbers()[2]);
    }

    public function testCreateChallengeUsesProvidedEquationParts(): void
    {
        $factory = new ChallengeFactory('en', array(0, 2));
        $challenge = $factory->createChallenge(2, '+', 1);
        $equation = $challenge->getEquation();

        self::assertSame('3', $challenge->getSolution());
        self::assertInstanceOf(Equation::class, $equation);
        self::assertSame(2, $equation->getLeft());
        self::assertSame('+', $equation->getSign());
        self::assertSame(1, $equation->getRight());
        self::assertMatchesRegularExpression('/^(two|2) (\+|plus) (one|1)$/', $challenge->getChallenge());
    }

    public function testCreateChallengeCanUseRandomDefaults(): void
    {
        $factory = new ChallengeFactory('en', array(1, 1));
        $challenge = $factory->createChallenge();
        $equation = $challenge->getEquation();

        self::assertInstanceOf(Equation::class, $equation);
        self::assertSame(1, $equation->getLeft());
        self::assertSame(1, $equation->getRight());
        self::assertContains($equation->getSign(), array('+', '-'));
        self::assertMatchesRegularExpression('/^(one|1) (\+|plus|-|minus) (one|1)$/', $challenge->getChallenge());
    }

    public function testCreatePositiveChallengeNormalizesNegativeSubtraction(): void
    {
        $factory = new ChallengeFactory('en', array(0, 5));
        $challenge = $factory->createPositiveChallenge(1, '-', 4);
        $equation = $challenge->getEquation();

        self::assertSame('3', $challenge->getSolution());
        self::assertSame(4, $equation->getLeft());
        self::assertSame(1, $equation->getRight());
        self::assertSame('-', $equation->getSign());
    }

    public function testCreatePositiveChallengePreservesNonSubtractionOrder(): void
    {
        $factory = new ChallengeFactory('en', array(0, 5));
        $challenge = $factory->createPositiveChallenge(1, '+', 4);
        $equation = $challenge->getEquation();

        self::assertSame('5', $challenge->getSolution());
        self::assertSame(1, $equation->getLeft());
        self::assertSame(4, $equation->getRight());
        self::assertSame('+', $equation->getSign());
    }

    public function testCreatePositiveChallengeCanUseRandomDefaults(): void
    {
        $factory = new ChallengeFactory('en', array(2, 2));
        $challenge = $factory->createPositiveChallenge();
        $equation = $challenge->getEquation();

        self::assertInstanceOf(Equation::class, $equation);
        self::assertSame(2, $equation->getLeft());
        self::assertSame(2, $equation->getRight());
        self::assertContains($equation->getSign(), array('+', '-'));
    }

    public function testCreateChallengeStringCanRenderLabelsAndRawValues(): void
    {
        $factory = new ChallengeFactory('en', array(0, 5));
        $equation = new Equation(2, '+', 3);
        $method = new ReflectionMethod(ChallengeFactory::class, 'createChallengeString');

        self::assertSame('two plus three', $method->invoke($factory, $equation, true, true, true));
        self::assertSame('2 + 3', $method->invoke($factory, $equation, false, false, false));
        self::assertSame('two + 3', $method->invoke($factory, $equation, true, false, false));
        self::assertSame('2 plus three', $method->invoke($factory, $equation, false, true, true));
    }
}
