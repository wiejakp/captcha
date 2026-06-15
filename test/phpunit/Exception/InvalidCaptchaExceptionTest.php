<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha\Tests\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use wiejakp\captcha\Exception\InvalidCaptchaException;
use wiejakp\captcha\Tests\AbstractUnitTestCase;

class InvalidCaptchaExceptionTest extends AbstractUnitTestCase
{
    public function testMessageKeyMatchesExpectedText(): void
    {
        $exception = new InvalidCaptchaException();

        self::assertInstanceOf(AuthenticationException::class, $exception);
        self::assertSame(
            "Your math doesn't check out. Please try logging in again.",
            $exception->getMessageKey()
        );
    }
}
