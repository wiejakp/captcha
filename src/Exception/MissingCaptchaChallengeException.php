<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class MissingCaptchaChallengeException extends AuthenticationException
{
    /**
     * @var string
     */
    protected $message = 'Missing captcha challenge.';

    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return $this->message;
    }
}
