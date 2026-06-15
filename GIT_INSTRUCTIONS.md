# Git Instructions

## Branching
- Use short-lived branches for feature or fix work.
- Keep each branch focused on one change set.

## Commits
- Prefer concise, imperative commit messages.
- Suggested style: `<type>: <summary>`
- Common types: `fix`, `feat`, `docs`, `refactor`, `test`, `chore`

## Pull Requests
- Describe the user-visible or API-visible effect.
- Mention compatibility implications for supported PHP and Symfony versions.
- Note any missing tests or follow-up work.

## Safety Checks
- Review diffs for accidental formatting churn.
- Avoid committing `vendor/`, lockfile noise, or unrelated file changes.
