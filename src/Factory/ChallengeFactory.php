<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha\Factory;

use wiejakp\captcha\Model\Challenge;
use wiejakp\captcha\Model\Equation;

class ChallengeFactory
{
    /**
     * @var int
     */
    const VALUE_MIN = 0;

    /**
     * @var int
     */
    const VALUE_MAX = 20;

    /**
     * @var string[]
     */
    const VALUE_SIGN = ['+', '-'];

    /**
     * @var string[]
     */
    const LABEL_SIGNS = [
        '+' => 'plus',
        '-' => 'minus',
    ];

    /**
     * @var string[]
     */
    const LABEL_NUMBERS = [
        0  => 'zero',
        1  => 'one',
        2  => 'two',
        3  => 'three',
        4  => 'four',
        5  => 'five',
        6  => 'six',
        7  => 'seven',
        8  => 'eight',
        9  => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
    ];

    /**
     * @param int|null    $left
     * @param string|null $sign
     * @param int|null    $right
     *
     * @return Challenge
     */
    public function createChallenge(?int $left = null, string $sign = null, int $right = null): Challenge
    {
        // init equation variables
        $left = $left ?? $this->getNumber();
        $sign = $sign ?? $this->getSign();
        $right = $right ?? $this->getNumber();

        // craft equation
        $equation = new Equation($left, $sign, $right);

        // craft challenge
        $challenge = new Challenge($this->createChallengeString($equation), $equation->getSolution());

        // assign equation to challenge in case we want to modify if
        $challenge->setEquation($equation);

        return $challenge;
    }

    /**
     * @param int|null    $left
     * @param string|null $sign
     * @param int|null    $right
     *
     * @return Challenge
     */
    public function createPositiveChallenge(?int $left = null, string $sign = null, int $right = null): Challenge
    {
        // init equation variables
        $left = $left ?? $this->getNumber();
        $sign = $sign ?? $this->getSign();
        $right = $right ?? $this->getNumber();

        // craft equation parts
        if ('-' === $sign) {
            $l = \max($left, $right);
            $r = \min($left, $right);
        } else {
            $l = $left;
            $r = $right;
        }

        // craft equation
        $equation = new Equation($l, $sign, $r);

        // craft challenge
        $challenge = new Challenge($this->createChallengeString($equation), $equation->getSolution());

        // assign equation to challenge in case we want to modify if
        $challenge->setEquation($equation);

        return $challenge;
    }

    /**
     * @return int
     */
    private function getNumber(): int
    {
        return \rand(self::VALUE_MIN, self::VALUE_MAX);
    }

    /**
     * @return string
     */
    private function getSign(): string
    {
        return self::VALUE_SIGN[\array_rand(self::VALUE_SIGN)];
    }

    /**
     * @param Equation  $equation
     * @param bool|null $leftLabel
     * @param bool|null $signLabel
     * @param bool|null $rightLabel
     *
     * @return string
     */
    private function createChallengeString(
        Equation $equation,
        bool $leftLabel = null,
        bool $signLabel = null,
        bool $rightLabel = null
    ): string {
        $left = null;
        $sign = null;
        $right = null;

        // randomise labels
        $leftLabel = \is_null($leftLabel) ? (bool)\rand(0, 1) : $leftLabel;
        $signLabel = \is_null($signLabel) ? (bool)\rand(0, 1) : $signLabel;
        $rightLabel = \is_null($rightLabel) ? (bool)\rand(0, 1) : $rightLabel;

        // convert random labels to strings
        $left = $leftLabel ? self::LABEL_NUMBERS[$equation->getLeft()] : $equation->getLeft();
        $sign = $signLabel ? self::LABEL_SIGNS[$equation->getSign()] : $equation->getSign();
        $right = $rightLabel ? self::LABEL_NUMBERS[$equation->getRight()] : $equation->getRight();

        return \sprintf('%s %s %s', $left, $sign, $right);
    }
}
