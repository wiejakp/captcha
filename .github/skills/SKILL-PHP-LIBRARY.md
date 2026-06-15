# SKILL-PHP-LIBRARY

Use this skill when working on `wiejakp/captcha`, a small Composer-based PHP library.

## Focus Areas

- Preserve public API stability unless breaking changes are explicitly requested.
- Keep compatibility with PHP `>=7.2.0` and `symfony/http-foundation` `^4.0|^5.0|^6.0` unless the
  task changes support targets.
- Prefer targeted edits in `src/` over broad refactors.

## Project Map

- `src/CaptchaService.php`: top-level service and session integration
- `src/Factory/ChallengeFactory.php`: localized equation and challenge generation
- `src/Model/`: value objects and equation/challenge state
- `src/Exception/`: domain exceptions

## Behavioral Risks

- Locale-sensitive `NumberFormatter` output
- Session persistence and key usage
- Positive challenge behavior for subtraction
- Changes that affect constructor defaults or method signatures

## Working Style

- Follow the existing strict-types and docblock-heavy style.
- Avoid adding dependencies unless clearly justified.
- Keep edits small and behaviorally obvious.
- When validation is possible, run syntax checks or available Composer-based tests.
