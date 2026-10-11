# Configuration

Configure the plugin under `CakeDto`, not `Dto`. Copy only the settings you need into `config/app.php` or use `Configure::write()` during bootstrap. The [example configuration](https://github.com/dereuromark/cakephp-dto/blob/master/config/app.example.php) lists the plugin options.

```php
'CakeDto' => [
    'strictTypes' => true,
    'immutable' => false,
],
```

Generation settings take effect after regeneration; they do not change already generated classes.

| Setting | Default | Purpose |
| --- | --- | --- |
| `engine` | `XmlEngine` | Definition parser implementing `EngineInterface`. |
| `suffix` | `Dto` | Generated class suffix. An empty string removes it; avoid reserved PHP class names. |
| `srcPath` | Application or plugin `src/` | Application output root. The generator adds a `Dto/` subdirectory; plugin generation uses its own `src/`. |
| `strictTypes` | `false` | Add `declare(strict_types=1)` to generated files. PHP scalar coercion also depends on the calling file's strict-types setting. |
| `scalarAndReturnTypes` | `true` | Generate scalar parameter and return declarations. |
| `typedConstants` | `false` | Use typed class constants. Enable only when the runtime is PHP 8.3 or newer. |
| `immutable` | `false` | Default immutability for definitions without an explicit setting. |
| `defaultCollectionType` | `\ArrayObject` | Default generated collection type. |
| `debug` | `false` | Include additional definition metadata in generated DTOs. |
| `assocKeyFields` | `slug`, `login`, `name`, `id` | Candidate string keys when inferring associative collections from example JSON. |
| `databaseTypeMap` | Importer defaults | Override database-to-DTO type mappings entry by entry. |
| `adminAccess` | Unset, deny | Closure that must return literal `true` for access to importer pages. |
| `finder` | Library `Finder` | Definition file finder. |
| `builder` | Plugin `Builder` | Class that builds definitions. |
| `generator` | Plugin `Generator` | Class that writes generated PHP. |
| `renderer` | Plugin `Renderer` | Class that renders generation templates. |

Nullable override settings such as `engine` and `builder` use their default class when unset or `null`. `defaultCollectionType` also uses its default when `null`.

## Separate generated code

```php
'CakeDto' => [
    'srcPath' => ROOT . DS . 'generated' . DS,
],
```

This writes application DTOs into `generated/Dto/`. With `--plugin`, the generator uses that plugin's `src/Dto/` instead; an application output override never redirects plugin classes into the same directory.

Add a matching Composer autoload entry alongside your existing application mapping:

```json
{
  "autoload": {
    "psr-4": {
      "App\\": "src/",
      "App\\Dto\\": "generated/Dto/"
    }
  }
}
```

Then refresh the autoloader:

```sh
composer dump-autoload
```

Changing the output directory does not change the generated namespace. Use `App.namespace`, a definition's nested name, or `--namespace` for that.

## Runtime defaults

The underlying library also provides runtime settings:

```php
use PhpCollective\Dto\Dto\Dto;

Dto::setDefaultKeyType(Dto::TYPE_UNDERSCORED);
```

The plugin sets the library's collection factory to create `Cake\Collection\Collection` instances and registers the CakePHP collection adapter during bootstrap. This does not change the generated `defaultCollectionType` from `\ArrayObject`; that is a separate generation setting.

## PHP compatibility

The default generated syntax supports the package's minimum PHP version. Typed constants require a newer runtime and are opt-in. Check generated output with `--confirm` under the PHP version used by your application, especially after changing templates or generation settings.
