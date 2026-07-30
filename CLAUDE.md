# CLAUDE.md for wiejakp/captcha

## Required Instructions

Claude must use the same repository instruction chain as other coding agents:

1. Read `AGENTS.md`.
2. Read `.github/copilot-instructions.md`.
3. Inspect `.github/skills/`.
4. Read the relevant `.github/skills/SKILL-*.md` file or files before planning or editing.
5. Inspect only files inside the resolved repository root.

Do not duplicate the full `AGENTS.md` rules here. `AGENTS.md` is the shared source of truth for
repository boundaries, coding style, validation expectations, git safety, and completion reporting.

## Claude-Specific Behavior

- Be explicit about file paths and commands.
- State which repository skills were read before making changes.
- Prefer small, direct edits that preserve the current library structure.
- Keep explanations concise and tied to the changed files.
- When unsure, inspect the relevant skill and local config before inferring behavior.

## Project Reminders

- This is a Composer PHP library, not a full application.
- Preserve public API compatibility unless a breaking change is explicitly requested.
- Respect `composer.json` support targets: PHP `^8.1`, `symfony/http-foundation` `^6.4`,
  `symfony/security-core` `^6.4`, and PHPUnit `^10.5`, unless the task explicitly changes support
  policy.
- Use `.github/skills/SKILL-PHP-LIBRARY.md` for source, API, session, localization, Composer, and
  validation-related tasks in this repository.

## Where to Look First

- `AGENTS.md` - shared coding-agent instructions
- `.github/copilot-instructions.md` - repository boundary rules and skill routing
- `.github/skills/` - task-specific AI instructions
- `composer.json` - package metadata and support constraints
- `src/` - library implementation
