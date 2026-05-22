# Contributing

:+1::tada: First off, thanks for taking the time to contribute! :tada::+1:

The issue tracker is not a support forum. Please keep issues to bug reports and
enhancement proposals. For general CakePHP support, see
<https://cakephp.org/pages/get-involved#userSupport>.

## How to Contribute

1. Fork the repository on GitHub and create a feature branch from `master`.
2. Add tests for any new functionality or bugfix (regression test).
3. Make sure the quality gates pass (see below).
4. Submit a pull request with a clear description of what changed and why.

## Development Setup

```bash
composer install
```

## Quality Gates

Please run these before submitting (the same checks CI runs):

```bash
composer test        # PHPUnit
composer stan        # PHPStan (run `composer stan-setup` once first)
composer cs-check    # Coding standards (`composer cs-fix` to auto-fix)
```

## Coding Standards

This plugin follows the PSR2R coding standards. Please make sure
`composer cs-check` is green before opening a pull request.

## Regenerating DTOs

After changing the generator or the test definitions, regenerate the test DTOs:

```bash
composer generate
```

## Debugging

With `assertTemplateContains()` you can check whether a given snippet is inside
the generated class. On failure it also writes, next to the file:

- an inline `.diff` to spot the difference more easily
- the expected snippet as `.expected`
- the actual code as `.result`

See the `tmp/compare/` directory.

Why snippets? Usually we only want to assert a small section (a method); comparing
whole files would introduce many side effects from newline/whitespace or unrelated
changes.

## Pull Request Guidelines

- Write clear, descriptive commit messages.
- Keep each pull request focused on a single feature or bug fix.
- Update the README/docs when you change user-facing behavior.

## Questions?

Open an issue for discussion if anything is unclear.
