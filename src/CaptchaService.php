<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use wiejakp\captcha\Factory\ChallengeFactory;
use wiejakp\captcha\Model\Challenge;

/**
 * Main Captcha Service Class
 */
class CaptchaService
{
    /**
     * @var Session
     */
    private $captchaSession;

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
     * @param SessionInterface|null $captchaSession
     * @param string                $captchaLocale
     * @param string                $captchaSessionKey
     * @param array                 $captchaNumberRange
     */
    public function __construct(
        SessionInterface $captchaSession = null,
        string $captchaLocale = 'en',
        string $captchaSessionKey = 'wiejakp\captcha\session_key',
        array $captchaNumberRange = [0, 20]
    ) {
        // init parameters
        $this->captchaSession = $captchaSession;
        $this->captchaLocale = $captchaLocale;
        $this->captchaSessionKey = $captchaSessionKey;
        $this->captchaNumberRange = $captchaNumberRange;

        // init service config from parameters
        $this->init();
    }

    /**
     * @throws \Exception
     */
    public function init(): void
    {
        // validate provided range numbers
        if($this->getCaptchaNumberRange()[1] < $this->getCaptchaNumberRange()[0]) {
            throw new \Exception('Provided range numbers are invalid.');
        }

        // create new session
        $this->captchaSession = $this->getCaptchaSession();

        // create new challenge factory with all required captcha values
        $this->captchaChallengeFactory = new ChallengeFactory(
            $this->getCaptchaLocale(),
            $this->getCaptchaNumberRange()
        );
    }

    /**
     * @return Session
     */
    private function getCaptchaSession(): Session
    {
        $session = $this->captchaSession;

        if (null === $session) {
            // create a new session
            $session = new Session();

            // start new session
            $session->start();
        }

        return $session;
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
    public function setCaptchaSessionChallenge(Challenge $challenge): self
    {
        $this->getCaptchaSession()->set($this->getCaptchaSessionKey(), $challenge);

        return $this;
    }

    /**
     * @return Challenge|null
     */
    public function getCaptchaChallenge(): ?Challenge
    {
        return $this->getCaptchaSession()->get($this->getCaptchaSessionKey());
    }

    /**
     * @param bool $saveToSession
     *
     * @return Challenge
     */
    public function createCaptchaChallenge(bool $saveToSession = true): Challenge
    {
        $challenge = $this->captchaChallengeFactory->createChallenge();

        // save challenge in the session
        if ($saveToSession) {
            $this->setCaptchaSessionChallenge($challenge);
        }

        return $challenge;
    }

    /**
     * @param bool $saveToSession
     *
     * @return Challenge
     */
    public function createPositiveCaptchaChallenge(bool $saveToSession = true): Challenge
    {
        $challenge = $this->captchaChallengeFactory->createPositiveChallenge();

        // save challenge in the session
        if ($saveToSession) {
            $this->setCaptchaSessionChallenge($challenge);
        }

        return $challenge;
    }

    /**
     * @param bool $saveToSession
     *
     * @return Challenge
     */
    public function createMockCaptchaChallenge(bool $saveToSession = true): Challenge
    {
        $challenge = $this->captchaChallengeFactory->createChallenge(0, null, 0);

        // save challenge in the session
        if ($saveToSession) {
            $this->setCaptchaSessionChallenge($challenge);
        }

        return $challenge;
    }
}
