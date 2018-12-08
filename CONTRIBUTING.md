# Contributing

:+1::tada: First off, thanks for taking the time to contribute! :tada::+1:


## I don't want to read this whole thing I just have a question!

The issue tracker is not a support forum. Issues should be related to bugs or enhancement proposals.
Please don't file an issue to ask a question. 
See https://cakephp.org/pages/get-involved#userSupport for generic CakePHP support.

## Getting Started

* Make sure you have a [GitHub account](https://github.com/signup/free)
* Fork the repository on GitHub.

## Making Changes

I am looking forward to your contributions. There are several ways to help out:
* Write missing test cases
* Write patches for bugs/features, preferably with test cases included

### Running Tests

```
composer test-setup
composer test
```

### Running CS

```
composer cs-check
```

and to fix possible issues
```
composer cs-fix
```

### Running PHPStan

```
composer phpstan-setup
composer phpstan
```

### Updating the test DTOs
```
composer generate
```

### Debugging

With `assertTemplateContains()` you can check if a certain snippet is inside the generated class.
For fails it will also create
- an inline `.diff` file to easier spot the difference
- the expected snippet as `.expected`
- the actual code as `.result` beside it

See the `tmp/compare/` dir.

Why snippets? Usually we only want to assert a small section (method), and if we compared a whole file, we would have many 
side effects from newline/whitespace or unrelated changes.
