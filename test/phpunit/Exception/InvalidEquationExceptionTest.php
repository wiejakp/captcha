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
use wiejakp\captcha\Exception\InvalidEquationException;
use wiejakp\captcha\Tests\AbstractUnitTestCase;

class InvalidEquationExceptionTest extends AbstractUnitTestCase
{
    public function testMessageKeyMatchesExpectedText(): void
    {
        $exception = new InvalidEquationException();

        self::assertInstanceOf(AuthenticationException::class, $exception);
        self::assertSame('Invalid equation exception.', $exception->getMessageKey());
    }
}
