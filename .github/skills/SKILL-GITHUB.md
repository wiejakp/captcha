# SKILL-GITHUB

Use this skill for git and GitHub workflows in `wiejakp/captcha`.

## Rules

- Use `git -C "$(git rev-parse --show-toplevel)" ...` for git commands, or equivalent logic based on
  the resolved repository root.
- Inspect `git status --short` before assuming the worktree is clean.
- Preserve unrelated user changes.
- Do not stage, commit, or push unless the user explicitly asks.
- Keep commits focused to one concern when commits are requested.

## Reporting

- Summarize changed files and validation clearly.
- Call out any uncommitted unrelated changes that were left untouched.
