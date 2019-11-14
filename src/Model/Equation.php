<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha\Model;

use wiejakp\captcha\Exception\InvalidEquationException;

class Equation
{
    /**
     * @var int
     */
    private $left;

    /**
     * @var string
     */
    private $sign;

    /**
     * @var int
     */
    private $right;

    /**
     * @var string
     */
    private $solution;

    /**
     * Equation constructor.
     *
     * @param int    $left
     * @param string $sign
     * @param int    $right
     *
     * @throws InvalidEquationException
     */
    public function __construct(int $left, string $sign, int $right)
    {
        $this->left = $left;
        $this->sign = $sign;
        $this->right = $right;

        try {
            $this->solution = (string)eval(\sprintf('return %s;', $this->getEquation()));
        } catch (\Exception $exception) {
            throw new InvalidEquationException(\sprintf('Invalid Equation: %s', $this->getEquation()));
        }
    }

    /**
     * @return int
     */
    public function getLeft(): int
    {
        return $this->left;
    }

    /**
     * @return string
     */
    public function getSign(): string
    {
        return $this->sign;
    }

    /**
     * @return int
     */
    public function getRight(): int
    {
        return $this->right;
    }

    /**
     * @return string
     */
    public function getEquation(): string
    {
        return \sprintf('%s %s %s', $this->getLeft(), $this->getSign(), $this->getRight());
    }

    /**
     * @return string
     */
    public function getSolution(): string
    {
        return $this->solution;
    }
}
