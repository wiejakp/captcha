<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha\Exception;

class InvalidEquationException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Invalid equation exception.';
}