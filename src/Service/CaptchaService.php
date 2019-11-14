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
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $sessionKey;

    /**
     * @var ChallengeFactory
     */
    private $challengeFactory;

    /**
     * CaptchaService constructor.
     *
     * @param SessionInterface $session
     * @param ChallengeFactory $challengeFactory
     * @param string           $locale
     * @param string           $sessionKey
     */
    public function __construct(
        SessionInterface $session,
        ChallengeFactory $challengeFactory,
        string $locale = 'en',
        string $sessionKey = 'wiejakp\captcha\session_key'
    ) {
        $this->session = $session;
        $this->challengeFactory = $challengeFactory;
        $this->locale = $locale;
        $this->sessionKey = $sessionKey;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getSessionKey(): string
    {
        return $this->sessionKey;
    }

    /**
     * @param Challenge $challenge
     *
     * @return self
     */
    public function setCaptchaSession(Challenge $challenge): self
    {
        $this->session->set($this->getSessionKey(), $challenge);

        return $this;
    }

    /**
     * @return Challenge|null
     */
    public function getCaptchaSession(): ?Challenge
    {
        return $this->session->get($this->getSessionKey());
    }

    /**
     * @return Challenge
     */
    public function getChallenge(): Challenge
    {
        return $this->challengeFactory->createChallenge();
    }

    /**
     * @return Challenge
     */
    public function getPositiveChallenge(): Challenge
    {
        return $this->challengeFactory->createPositiveChallenge();
    }

    /**
     * @return Challenge
     */
    public function getMockChallenge(): Challenge
    {
        return $this->challengeFactory->createChallenge(0, null, 0);
    }
}
