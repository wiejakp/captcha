<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha\Model;

class Challenge
{
    /**
     * @var Equation
     */
    private $equation;

    /**
     * @var string
     */
    private $challenge;

    /**
     * @var string
     */
    private $solution;

    /**
     * Challenge constructor.
     *
     * @param string $challenge
     * @param string $solution
     */
    public function __construct(string $challenge, string $solution)
    {
        $this->challenge = $challenge;
        $this->solution = $solution;
    }

    /**
     * @return string
     */
    public function getChallenge(): string
    {
        return $this->challenge;
    }

    /**
     * @return string
     */
    public function getSolution(): string
    {
        return $this->solution;
    }

    /**
     * @return Equation|null
     */
    public function getEquation(): ?Equation
    {
        return $this->equation;
    }

    /**
     * @param Equation|null $equation
     *
     * @return Challenge
     */
    public function setEquation(?Equation $equation): self
    {
        $this->equation = $equation;
        return $this;
    }

    /**
     * @param string $solution
     *
     * @return bool
     */
    public function isEqual(string $solution): bool
    {
        return $this->getSolution() == \trim($solution);
    }
}
