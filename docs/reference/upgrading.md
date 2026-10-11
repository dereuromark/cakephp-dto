# Upgrading

This branch targets CakePHP 5. Composer's [package constraints](https://github.com/dereuromark/cakephp-dto/blob/master/composer.json) define supported versions; the [version map](https://github.com/dereuromark/cakephp-dto/wiki#cakephp-version-map) covers older CakePHP branches.

After upgrading the plugin or `php-collective/dto`, regenerate classes and review the diff:

```sh
bin/cake dto generate --confirm
bin/cake dto generate --dry-run
```

Keep application tests around required fields, key conversion, nested collections and custom value-object serialization. Generated method signatures can change when definitions or generation options change.

## Current documentation corrections

Use `CakeDto` as the configuration key. The old `Dto` example did not configure this plugin. `CakeDto.srcPath` selects an output root, and the generator adds `Dto/`; the Composer mapping must point at that subdirectory.

`CakeDto.keyType` was an unused example setting. Use the key-format argument on DTO methods or the library's `Dto::setDefaultKeyType()` runtime API instead.

Use CakePHP's current `Date` and `DateTime` classes in new definitions rather than the older `FrozenDate` and `FrozenTime` aliases. Review whether your existing generated objects need a migration before changing their declared type.

`fromUnserialized()` accepts JSON. Use PHP's `unserialize()` for values created by PHP's `serialize()`, with the appropriate allowed classes.

The default database importer maps decimal columns to strings. Regenerating an imported definition can change previous float fields; configure an explicit override if you need to keep that older type temporarily.

## Request-resolution migration

The plugin's former `MapRequestDto` attribute and application integration were removed. CakePHP's `RequestToDto` attribute is available starting in 5.4, not 5.2. Earlier supported CakePHP releases should map request data explicitly:

```php
$product = ProductDto::createFromArray($this->request->getData());
```

On CakePHP 5.4 or newer, use the core attribute and its `RequestToDtoSource` enum. See [Request parameters](/guide/integration#request-parameters).

## CakePHP 6 preparation

The package currently requires CakePHP 5; these changes do not declare CakePHP 6 support. Keep generated files in sync, use current CakePHP APIs and run your application's tests on its supported PHP versions. Typed class constants remain disabled by default for compatibility with the minimum PHP runtime.
