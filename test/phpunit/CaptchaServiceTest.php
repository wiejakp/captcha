<?php
/**
 * (c) Przemek Wiejak <przemek@wiejak.app>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace wiejakp\captcha\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\UsesClass;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use wiejakp\captcha\CaptchaService;
use wiejakp\captcha\Factory\ChallengeFactory;
use wiejakp\captcha\Model\Challenge;

#[CoversClass(CaptchaService::class)]
#[UsesClass(ChallengeFactory::class)]
#[UsesClass(Challenge::class)]
class CaptchaServiceTest extends AbstractUnitTestCase
{
    public function testConstructorInitializesConfiguredValues(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $service = new CaptchaService($session, 'pl', 'captcha_key', array(2, 4));

        self::assertSame('pl', $service->getCaptchaLocale());
        self::assertSame('captcha_key', $service->getCaptchaSessionKey());
        self::assertSame(array(2, 4), $service->getCaptchaNumberRange());
        self::assertSame($session, $this->readObjectProperty($service, 'captchaSession'));
        self::assertInstanceOf(
            ChallengeFactory::class,
            $this->readObjectProperty($service, 'captchaChallengeFactory')
        );
    }

    public function testConstructorAcceptsAnySessionInterfaceImplementation(): void
    {
        $session = $this->createMock(SessionInterface::class);

        $service = new CaptchaService($session);

        self::assertSame($session, $this->readObjectProperty($service, 'captchaSession'));
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function testConstructorStartsSessionWhenNoneProvided(): void
    {
        $service = new CaptchaService(null, 'en', 'session_key', array(0, 1));
        $session = $this->readObjectProperty($service, 'captchaSession');

        self::assertInstanceOf(Session::class, $session);
        self::assertTrue($session->isStarted());

        if (\PHP_SESSION_ACTIVE === \session_status()) {
            \session_write_close();
        }
    }

    public function testInitRejectsInvalidRanges(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Provided range numbers are invalid.');

        new CaptchaService(new Session(new MockArraySessionStorage()), 'en', 'key', array(4, 2));
    }

    public function testSettersReturnSelfAndUpdateValues(): void
    {
        $service = new CaptchaService(new Session(new MockArraySessionStorage()));

        self::assertSame($service, $service->setCaptchaLocale('de'));
        self::assertSame($service, $service->setCaptchaSessionKey('changed_key'));
        self::assertSame($service, $service->setCaptchaNumberRange(array(9, 9)));

        self::assertSame('de', $service->getCaptchaLocale());
        self::assertSame('changed_key', $service->getCaptchaSessionKey());
        self::assertSame(array(9, 9), $service->getCaptchaNumberRange());
    }

    public function testCanStoreAndReadChallengeFromSession(): void
    {
        $service = new CaptchaService(new Session(new MockArraySessionStorage()));
        $challenge = new Challenge('1 + 1', '2');

        self::assertSame($service, $service->setCaptchaSessionChallenge($challenge));
        self::assertSame($challenge, $service->getCaptchaChallenge());
    }

    public function testCreateCaptchaChallengeCanSkipSessionStorage(): void
    {
        $service = new CaptchaService(new Session(new MockArraySessionStorage()));
        $challenge = new Challenge('skip', '7');
        $factory = new CaptchaServiceTestChallengeFactory($challenge, $challenge, $challenge);
        $this->writeObjectProperty($service, 'captchaChallengeFactory', $factory);

        $created = $service->createCaptchaChallenge(false);

        self::assertSame($challenge, $created);
        self::assertNull($service->getCaptchaChallenge());
        self::assertSame(1, $factory->createChallengeCalls);
    }

    public function testCreateCaptchaChallengeStoresSessionByDefault(): void
    {
        $service = new CaptchaService(new Session(new MockArraySessionStorage()));
        $challenge = new Challenge('store', '8');
        $factory = new CaptchaServiceTestChallengeFactory($challenge, $challenge, $challenge);
        $this->writeObjectProperty($service, 'captchaChallengeFactory', $factory);

        $created = $service->createCaptchaChallenge();

        self::assertSame($challenge, $created);
        self::assertSame($challenge, $service->getCaptchaChallenge());
        self::assertSame(1, $factory->createChallengeCalls);
    }

    public function testCreatePositiveCaptchaChallengeUsesFactoryAndStoresToSession(): void
    {
        $service = new CaptchaService(new Session(new MockArraySessionStorage()));
        $challenge = new Challenge('positive', '9');
        $factory = new CaptchaServiceTestChallengeFactory(
            new Challenge('default', '1'),
            $challenge,
            new Challenge('mock', '0')
        );
        $this->writeObjectProperty($service, 'captchaChallengeFactory', $factory);

        $created = $service->createPositiveCaptchaChallenge();

        self::assertSame($challenge, $created);
        self::assertSame($challenge, $service->getCaptchaChallenge());
        self::assertSame(1, $factory->createPositiveChallengeCalls);
    }

    public function testCreateMockCaptchaChallengeUsesFactoryAndCanSkipSessionStorage(): void
    {
        $service = new CaptchaService(new Session(new MockArraySessionStorage()));
        $challenge = new Challenge('mock challenge', '0');
        $factory = new CaptchaServiceTestChallengeFactory(
            new Challenge('default', '1'),
            new Challenge('positive', '2'),
            $challenge
        );
        $this->writeObjectProperty($service, 'captchaChallengeFactory', $factory);

        $created = $service->createMockCaptchaChallenge(false);

        self::assertSame($challenge, $created);
        self::assertNull($service->getCaptchaChallenge());
        self::assertSame(1, $factory->createMockChallengeCalls);
    }

    public function testCreateMockCaptchaChallengeStoresToSessionByDefault(): void
    {
        $service = new CaptchaService(new Session(new MockArraySessionStorage()));
        $challenge = new Challenge('mock challenge', '0');
        $factory = new CaptchaServiceTestChallengeFactory(
            new Challenge('default', '1'),
            new Challenge('positive', '2'),
            $challenge
        );
        $this->writeObjectProperty($service, 'captchaChallengeFactory', $factory);

        $created = $service->createMockCaptchaChallenge();

        self::assertSame($challenge, $created);
        self::assertSame($challenge, $service->getCaptchaChallenge());
        self::assertSame(1, $factory->createMockChallengeCalls);
    }
}

class CaptchaServiceTestChallengeFactory extends ChallengeFactory
{
    /**
     * @var Challenge
     */
    private Challenge $challenge;

    /**
     * @var Challenge
     */
    private Challenge $positiveChallenge;

    /**
     * @var Challenge
     */
    private Challenge $mockChallenge;

    /**
     * @var int
     */
    public int $createChallengeCalls = 0;

    /**
     * @var int
     */
    public int $createPositiveChallengeCalls = 0;

    /**
     * @var int
     */
    public int $createMockChallengeCalls = 0;

    public function __construct(Challenge $challenge, Challenge $positiveChallenge, Challenge $mockChallenge)
    {
        parent::__construct('en', array(0, 0));
        $this->challenge = $challenge;
        $this->positiveChallenge = $positiveChallenge;
        $this->mockChallenge = $mockChallenge;
    }

    public function createChallenge(
        ?int $left = null,
        ?string $sign = null,
        ?int $right = null
    ): Challenge {
        if (0 === $left && 0 === $right) {
            ++$this->createMockChallengeCalls;

            return $this->mockChallenge;
        }

        ++$this->createChallengeCalls;

        return $this->challenge;
    }

    public function createPositiveChallenge(
        ?int $left = null,
        ?string $sign = null,
        ?int $right = null
    ): Challenge {
        ++$this->createPositiveChallengeCalls;

        return $this->positiveChallenge;
    }
}
