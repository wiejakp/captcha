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

class CaptchaService
{
    /**
     * @var string
     */
    const CAPTCHA_SESSION_KEY = 'component_captcha';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ChallengeFactory
     */
    private $challengeFactory;

    /**
     * CaptchaService constructor.
     *
     * @param SessionInterface $session
     * @param ChallengeFactory $challengeFactory
     */
    public function __construct(SessionInterface $session, ChallengeFactory $challengeFactory)
    {
        $this->session = $session;
        $this->challengeFactory = $challengeFactory;
    }

    /**
     * @param Challenge $challenge
     *
     * @return self
     */
    public function setCaptchaSession(Challenge $challenge): self
    {
        $this->session->set(self::CAPTCHA_SESSION_KEY, $challenge);

        return $this;
    }

    /**
     * @return Challenge|null
     */
    public function getCaptchaSession(): ?Challenge
    {
        return $this->session->get(self::CAPTCHA_SESSION_KEY);
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
