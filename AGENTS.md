# AGENTS.md

## Required Instruction Lookup

Before answering, planning, editing, searching, creating files, deleting files, or running commands,
read and follow these files when they exist:

1. `AGENTS.md`
2. `.github/copilot-instructions.md`
3. `.github/instructions/**/*.instructions.md`
4. Relevant `.github/skills/SKILL-*.md` files
5. Relevant project documentation for the touched area

Use `.github/copilot-instructions.md` as the routing file for required skills.

If instructions conflict, follow this priority:

1. User request in the current prompt
2. Existing code style in the modified file
3. `AGENTS.md`
4. `.github/copilot-instructions.md`
5. Relevant `.github/skills/SKILL-*.md` files
6. Other project documentation

Existing code style takes precedence unless the user explicitly requests a style change.

## Repository Boundary

All inspection, reading, searching, editing, file creation, deletion, and command execution must stay
inside the resolved repository root unless the user explicitly requests cross-repository work.

Repository boundary workflow:

1. Resolve the repository root automatically.
2. Treat the resolved root as the only allowed filesystem scope.
3. Inspect `.github/copilot-instructions.md`.
4. Inspect `.github/skills/`.
5. Read the relevant skill file or files.
6. Inspect only repository files relevant to the task.
7. Only then propose changes, modify files, search files, or run commands.

If the repository root cannot be resolved confidently, stop and report that instead of guessing.

## Required Skill Lookup

Before working on a task, inspect `.github/skills/` and explicitly read the most relevant skill
file or files.

Common skill routing:

- General PHP library work, public API changes, service behavior, challenge generation, session
  handling, localization, Composer metadata, or source changes under `src/`:
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

If no skill matches exactly, choose the closest applicable skill and state why before continuing.

## Required Response Preamble

For repository tasks, start with:

```text
Repository root: <resolved repository root>
Boundary: only files under the resolved repository root may be inspected or changed
Skills reviewed: <comma-separated SKILL files>
Primary skill: <SKILL file>
```

## General Behavior

- Keep edits narrowly scoped to the user request.
- Preserve existing structure, naming, comments, and behavior unless the change requires otherwise.
- Do not refactor unrelated code.
- Do not add new abstractions unless they remove real complexity or match an existing pattern.
- Do not modify generated dependency directories such as `vendor/`.
- Do not commit, stage, or push unless the user explicitly asks.
- Do not print secrets from local-only configuration.
- Preserve unrelated user changes in the worktree.

## Formatting Rules

- Use LF line endings, UTF-8, final newlines, and trim trailing whitespace.
- Use 4-space indentation unless nearby files use a different local style.
- Keep line length at or below 120 characters when practical.
- Preserve PHP style already used in the touched file.

## Project Overview

`wiejakp/captcha` is a small Composer PHP library for generating and validating text-based captcha
math challenges.

Important repository constraints:

- PHP: `composer.json` currently requires `>=7.2.0`.
- Symfony component support: `symfony/http-foundation` currently supports `^4.0|^5.0|^6.0`.
- Main namespace: `wiejakp\\captcha`.
- Core service: `src/CaptchaService.php`.
- Challenge generation: `src/Factory/ChallengeFactory.php`.
- Domain models: `src/Model/`.
- Exceptions: `src/Exception/`.
- Locale-sensitive behavior uses `NumberFormatter`.
- Positive challenge behavior must keep subtraction results non-negative.
- Session behavior should remain explicit and easy to reason about.

## Completion Rules

- Before finishing, summarize which skills were used, which files were inspected, what changed, and
  what validation was run.
- If validation could not be run, state that clearly.
