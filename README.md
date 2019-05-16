# DTO Generator for PHP

[![Build Status](https://travis-ci.org/dereuromark/cakephp-dto.svg?branch=master)](https://travis-ci.org/dereuromark/cakephp-dto)
[![codecov](https://img.shields.io/codecov/c/github/dereuromark/cakephp-dto/master.svg)](https://codecov.io/gh/dereuromark/cakephp-dto)
[![Latest Stable Version](https://poser.pugx.org/dereuromark/cakephp-dto/v/stable.svg)](https://packagist.org/packages/dereuromark/cakephp-dto)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/dereuromark/cakephp-dto/license.svg)](https://packagist.org/packages/dereuromark/cakephp-dto)
[![Total Downloads](https://poser.pugx.org/dereuromark/cakephp-dto/d/total.svg)](https://packagist.org/packages/dereuromark/cakephp-dto)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)

A Data Transfer Object (DTO) is an object used to pass typed data between layers in your application, similar in 
concept to [Structs](https://en.wikipedia.org/wiki/Struct_(C_programming_language)) in C, Martin Fowler's [Transfer
 Objects](http://martinfowler.com/eaaCatalog/dataTransferObject.html), or [Value Objects](https://en.wikipedia.org/wiki/Value_object).

The goal of this package is to structure "unstructured data", replacing simple (associative) arrays with a more speaking solution.

- By making all fields typeable, we can be sure that their values are never something we didn't expect. 
Especially with PHP 7.2+ now and more strict typehinting moving forward this is rather important to detect and fail early.
- We can have full IDE autocomplete and typehinting.
- We can use tools like PHPStan to statically analyze the code (more strictly).
- We can simplify the logic where required fields will now just throw a meaningful exception.
- We can work with different inflections of field names more easily.
- Easy way of immutable DTOs with required fields, to trust them in following code.

For more see [Motivation and Background](/docs/Motivation.md).

This plugin will provide you with a tool to quickly generate custom and optimized DTOs for your special use cases.

This branch is for use with **PHP 7.2+**.

## Examples

Live examples in the Sandbox: [Github Demo](https://sandbox.dereuromark.de/sandbox/dto-examples/github).

See [Examples](docs/Examples.md) for basic, immutable and complex entity use cases.
The generated demo DTOs of those are in [tests/test_app/src/Dto/](tests/test_app/src/Dto/). 

Note: Typehinting is enabled and strict types disabled by default.

## Installation

You can install this plugin into your CakePHP application using [Composer](https://getcomposer.org/).

The recommended way to install is:

```
composer require dereuromark/dto
```


## Usage

See [Docs](/docs) for details.
