# wiejakp/captcha

Captcha library for PHP applications.

## Requirements

- PHP `^8.1`
- PHP Intl extension
- Symfony HttpFoundation `^6.4`
- Symfony Security Core `^6.4`

PCOV or Xdebug is required only when generating code coverage.

## Installation

```bash
composer require wiejakp/captcha:^2.0
```

## Usage

```php
use wiejakp\captcha\CaptchaService;

$captcha = new CaptchaService();
$challenge = $captcha->createPositiveCaptchaChallenge();

echo $challenge->getChallenge();

if ($challenge->isEqual($submittedAnswer)) {
    // The answer is valid.
}
```

Challenges are stored in the configured Symfony session by default. Pass `false` to a creation
method when the generated challenge should not be stored.

## Tests

Run the suite under PHP 8.1:

```bash
composer test
```

With PCOV enabled, generate coverage using:

```bash
composer test:coverage
```
