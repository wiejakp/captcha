<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use wiejakp\captcha\Exception\MissingCaptchaChallengeException;
use wiejakp\captcha\Tests\AbstractUnitTestCase;

#[CoversClass(MissingCaptchaChallengeException::class)]
class MissingCaptchaChallengeExceptionTest extends AbstractUnitTestCase
{
    public function testMessageKeyMatchesExpectedText(): void
    {
        $exception = new MissingCaptchaChallengeException();

        self::assertInstanceOf(AuthenticationException::class, $exception);
        self::assertSame('Missing captcha challenge.', $exception->getMessageKey());
    }
}
