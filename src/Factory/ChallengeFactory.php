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
use NumberFormatter;

class ChallengeFactory
{
    /**
     * @var string
     */
    private $value_locale = '';

    /**
     * @var int[]
     */
    private $value_range = [0, 0];

    /**
     * @var string[]
     */
    private $value_signs = [];

    /**
     * @var string[]
     */
    private $value_numbers = [];

    /**
     * ChallengeFactory constructor.
     *
     * @param string $value_locale
     * @param array  $value_range
     */
    public function __construct(string $value_locale, array $value_range)
    {
        // initiate parameters
        $this->value_locale = $value_locale;
        $this->value_range = $value_range;

        // initiate factory values
        $this->init();
    }

    private function init(): void
    {
        // create sign values - i could not find a way to translate this by locale
        $this->value_signs = [
            '+' => 'plus',
            '-' => 'minus',
        ];

        // init number formatter
        $formatter = new NumberFormatter($this->getValueLocale(), NumberFormatter::SPELLOUT);

        // generate number value array with translated labels per locale
        for ($index = $this->getValueRange()[0]; $index <= $this->getValueRange()[1]; $index++) {
            $this->value_numbers[$index] = $formatter->format($index);
        }
    }

    /**
     * @return string
     */
    public function getValueLocale(): string
    {
        return $this->value_locale;
    }

    /**
     * @return int[]
     */
    public function getValueRange(): array
    {
        return $this->value_range;
    }

    /**
     * @return string[]
     */
    public function getValueSigns(): array
    {
        return $this->value_signs;
    }

    /**
     * @return string[]
     */
    public function getValueNumbers(): array
    {
        return $this->value_numbers;
    }


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
        return \rand($this->getValueRange()[0], $this->getValueRange()[1]);
    }

    /**
     * @return string
     */
    private function getSign(): string
    {
        return \array_rand($this->getValueSigns());
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
        $left = $leftLabel ? $this->getValueNumbers()[$equation->getLeft()] : $equation->getLeft();
        $sign = $signLabel ? $this->getValueSigns()[$equation->getSign()] : $equation->getSign();
        $right = $rightLabel ? $this->getValueNumbers()[$equation->getRight()] : $equation->getRight();

        return \sprintf('%s %s %s', $left, $sign, $right);
    }
}
