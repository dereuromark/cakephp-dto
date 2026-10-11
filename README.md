# CakePHP DTO plugin

[![CI](https://github.com/dereuromark/cakephp-dto/actions/workflows/ci.yml/badge.svg?branch=cake6)](https://github.com/dereuromark/cakephp-dto/actions/workflows/ci.yml?query=branch%3Acake6)
[![codecov](https://img.shields.io/codecov/c/github/dereuromark/cakephp-dto/cake6.svg)](https://codecov.io/gh/dereuromark/cakephp-dto)
[![Latest Stable Version](https://poser.pugx.org/dereuromark/cakephp-dto/v/stable.svg)](https://packagist.org/packages/dereuromark/cakephp-dto)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.5-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/dereuromark/cakephp-dto/license.svg)](LICENSE)
[![Total Downloads](https://poser.pugx.org/dereuromark/cakephp-dto/d/total.svg)](https://packagist.org/packages/dereuromark/cakephp-dto)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-purple.svg?style=flat-square)](https://github.com/php-fig-rectified/fig-rectified-standards)


Generate typed data transfer objects from XML, PHP, YAML or NEON definitions. The plugin integrates [php-collective/dto](https://github.com/php-collective/dto) with CakePHP and adds console generation, entity mapping, JSON responses and schema import tools.

Generated DTOs have named getters, setters or immutable `with...()` methods. They carry data between application layers; ORM entities still represent persisted records.

## Documentation

This development branch targets CakePHP 6 and PHP 8.5. Read the
[branch installation guide](docs/guide/installation.md). The published website
below covers CakePHP 5.

- [Installation](https://dereuromark.github.io/cakephp-dto/guide/installation)
- [Getting started](https://dereuromark.github.io/cakephp-dto/guide/getting-started)
- [Definitions](https://dereuromark.github.io/cakephp-dto/guide/definitions)
- [CakePHP integration](https://dereuromark.github.io/cakephp-dto/guide/integration)
- [Commands and CI](https://dereuromark.github.io/cakephp-dto/reference/commands)

See the [version map](https://github.com/dereuromark/cakephp-dto/wiki#cakephp-version-map) for older CakePHP branches and [Composer constraints](composer.json) for package compatibility.

## Examples

Try the [Sandbox demo](https://sandbox.dereuromark.de/sandbox/dto-examples/github), browse [definition examples](docs/examples/), or inspect the [generated test DTOs](tests/test_app/src/Dto/).
