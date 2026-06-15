<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class AbstractUnitTestCase extends TestCase
{
    /**
     * @param object $object
     * @param string $property
     *
     * @return mixed
     */
    protected function readObjectProperty(object $object, string $property)
    {
        $reflection = new ReflectionClass($object);
        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }

    /**
     * @param object $object
     * @param string $property
     * @param mixed  $value
     */
    protected function writeObjectProperty(object $object, string $property, $value): void
    {
        $reflection = new ReflectionClass($object);
        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    }
}
