For any task in this repository:

- Resolve the repository root dynamically.
- Keep inspection, search, commands, and edits inside the resolved repository root unless the user
  explicitly requests cross-repository work.
- Inspect `.github/skills/` before planning or editing.
- Explicitly read the relevant `SKILL-*.md` file or files before proceeding.
- Treat repository skills as mandatory task instructions.
- If no skill matches exactly, choose the closest applicable skill and state why.

Project overview:

- `wiejakp/captcha` is a Composer PHP library for generating captcha math challenges.
- The public API centers on `src/CaptchaService.php`.
- Challenge and equation generation live under `src/Factory/` and `src/Model/`.
- Session storage uses Symfony HttpFoundation sessions.
- Locale-sensitive number labels are generated with `NumberFormatter`.
- Positive challenge generation must preserve non-negative subtraction results.

Required skill routing:

- General PHP library work, API changes, service behavior, challenge generation, session handling,
  localization, Composer metadata, or source changes under `src/`:
  - `.github/skills/SKILL-PHP-LIBRARY.md`
- Generated or modified PHP code:
  - `.github/skills/SKILL-PHP-LIBRARY.md`
- Composer commands, dependency changes, or PHP runtime/tooling:
  - `.github/skills/SKILL-PHP-LIBRARY.md`
- Creating or updating tests:
  - `.github/skills/SKILL-PHP-LIBRARY.md`
- Generated or modified skills:
  - `.github/skills/SKILL-VALIDATOR.md`
- Git or GitHub commands:
  - `.github/skills/SKILL-GITHUB.md`

Repository runtime rules:

- `composer.json` is authoritative for supported PHP and dependency constraints.
- Current PHP support target is `>=7.2.0`.
- Current Symfony HttpFoundation support target is `^4.0|^5.0|^6.0`.
- Do not add new dependencies unless the task clearly requires them.
- If no test command exists, validate changed PHP files with syntax checks when practical.

Repository safety rules:

- Do not print secrets from local-only configuration.
- Do not modify `vendor/` or generated files unless the user explicitly asks and the task requires it.
- Do not stage, commit, or push unless the user explicitly asks.
- Use `git -C "$(git rev-parse --show-toplevel)" ...` for git commands, or equivalent logic based on
  the resolved repository root.

Coding style:

- Keep line length at or below 120 characters when practical across PHP, Markdown, and config files.
- Preserve the local style of nearby files.
- Prefer small, readable, boring implementations over broad refactors.

Completion rules:

- Before finishing, summarize which skills were used, which files were inspected, what changed, and
  what validation was run.
- If validation could not be run, state that clearly.
