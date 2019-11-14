<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha\Service;

use wiejakp\captcha\Factory\ChallengeFactory;
use wiejakp\captcha\Model\Challenge;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Main Captcha Service Class
 */
class CaptchaService
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ChallengeFactory
     */
    private $captchaChallengeFactory;

    /**
     * @var string
     */
    private $captchaLocale;

    /**
     * @var string
     */
    private $captchaSessionKey;

    /**
     * @var int[]
     */
    private $captchaNumberRange;

    /**
     * CaptchaService constructor.
     *
     * @param SessionInterface $session
     * @param ChallengeFactory $challengeFactory
     * @param string           $captchaLocale
     * @param string           $captchaSessionKey
     * @param int[]            $captchaNumberRange
     */
    public function __construct(
        SessionInterface $session,
        string $captchaLocale = 'en',
        string $captchaSessionKey = 'wiejakp\captcha\session_key',
        array $captchaNumberRange = [0, 20]
    ) {
        // init parameters
        $this->session = $session;
        $this->captchaLocale = $captchaLocale;
        $this->captchaSessionKey = $captchaSessionKey;
        $this->captchaNumberRange = $captchaNumberRange;

        // init service config from parameters
        $this->init();
    }

    /**
     * @return CaptchaService
     */
    public function init(): self
    {
        // create new challenge factory with all required captcha values
        $this->captchaChallengeFactory = new ChallengeFactory(
            $this->getCaptchaLocale(),
            $this->getCaptchaNumberRange()
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getCaptchaLocale(): string
    {
        return $this->captchaLocale;
    }

    /**
     * @param string $captchaLocale
     *
     * @return self
     */
    public function setCaptchaLocale(string $captchaLocale): self
    {
        $this->captchaLocale = $captchaLocale;
        return $this;
    }

    /**
     * @return string
     */
    public function getCaptchaSessionKey(): string
    {
        return $this->captchaSessionKey;
    }

    /**
     * @param string $captchaSessionKey
     *
     * @return self
     */
    public function setCaptchaSessionKey(string $captchaSessionKey): self
    {
        $this->captchaSessionKey = $captchaSessionKey;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getCaptchaNumberRange(): array
    {
        return $this->captchaNumberRange;
    }

    /**
     * @param int[] $captchaNumberRange
     *
     * @return self
     */
    public function setCaptchaNumberRange(array $captchaNumberRange): self
    {
        $this->captchaNumberRange = $captchaNumberRange;
        return $this;
    }

    /**
     * @param Challenge $challenge
     *
     * @return self
     */
    public function setCaptchaSession(Challenge $challenge): self
    {
        $this->session->set($this->getCaptchaSessionKey(), $challenge);

        return $this;
    }

    /**
     * @return Challenge|null
     */
    public function getCaptchaSession(): ?Challenge
    {
        return $this->session->get($this->getCaptchaSessionKey());
    }

    /**
     * @return Challenge
     */
    public function getChallenge(): Challenge
    {
        return $this->captchaChallengeFactory->createChallenge();
    }

    /**
     * @return Challenge
     */
    public function getPositiveChallenge(): Challenge
    {
        return $this->captchaChallengeFactory->createPositiveChallenge();
    }

    /**
     * @return Challenge
     */
    public function getMockChallenge(): Challenge
    {
        return $this->captchaChallengeFactory->createChallenge(0, null, 0);
    }
}
