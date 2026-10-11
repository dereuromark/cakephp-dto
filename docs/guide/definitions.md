# Definitions

The generator reads `config/dto.xml` and XML files directly inside `config/dto/`. The default finder accepts any `.xml` filename in that directory, sorts those files alphabetically, then reads `config/dto.xml` last. Nested definition directories are not scanned.

## XML

Use the plugin's `cakephp-dto` namespace. The bundled [XSD](https://github.com/dereuromark/cakephp-dto/blob/master/config/dto.xsd) lists the supported attributes and can provide completion in an XML editor.

```xml
<?xml version="1.0"?>
<dtos xmlns="cakephp-dto">
    <dto name="Product">
        <field name="title" type="string"/>
        <field name="stockCount" type="int" defaultValue="0"/>
        <field name="published" type="bool" defaultValue="false"/>
        <field name="created" type="\Cake\I18n\DateTime"/>
        <field name="tags" type="string[]"/>
    </dto>
</dtos>
```

Field names are camelCase. Scalar fields are nullable unless required or otherwise configured. A default value initializes the field without a caller supplying it.

## Types and nested DTOs

Use PHP scalar types such as `string`, `int`, `float`, `bool` and `mixed`. `array` accepts untyped arrays; `string[]` declares an array of strings. A fully qualified class name declares an object field. Another DTO's definition name declares a nested DTO:

```xml
<dto name="Owner">
    <field name="name" type="string"/>
</dto>
<dto name="Product">
    <field name="owner" type="Owner"/>
    <field name="reference" type="int|string"/>
</dto>
```

`Owner` resolves to the generated `OwnerDto` in the same DTO namespace. Union types declare more than one accepted type. Use [Collections](/guide/collections) for multiple nested DTOs.

## Required fields and immutability

```xml
<dto name="Product" immutable="true">
    <field name="title" type="string" required="true"/>
    <field name="stockCount" type="int" required="true" defaultValue="0"/>
</dto>
```

The constructor requires a non-null title. The default stock count satisfies that field's requirement. A required field's getter returns its declared type directly; a separate `OrFail` getter is unnecessary.

```php
use App\Dto\ProductDto;

$product = new ProductDto(['title' => 'Notebook']);
$updated = $product->withTitle('Sketchbook');

$originalTitle = $product->getTitle(); // 'Notebook'
$updatedTitle = $updated->getTitle(); // 'Sketchbook'
```

`with...()` returns another DTO; keep its return value. Nested mutable objects can still change independently of an immutable outer DTO. Choose immutable value objects and collections appropriate to your data.

## Inheritance and namespaces

```xml
<dto name="Product">
    <field name="title" type="string"/>
</dto>
<dto name="DigitalProduct" extends="Product">
    <field name="downloadUrl" type="string"/>
</dto>
```

The generated child extends `ProductDto`. For a nested namespace, use a name such as `Catalog/Product`; generation creates `src/Dto/Catalog/ProductDto.php`. The application's namespace comes from `App.namespace`; `--plugin` selects the plugin namespace and `--namespace` overrides it.

## Mapping and serialization

Use `mapFrom` and `mapTo` when an external API's key differs from the DTO field:

```xml
<field name="firstName" type="string" mapFrom="first_name" mapTo="first_name"/>
```

This field-specific mapping differs from applying a key format to the entire input array.

Object fields can use a factory instead of their constructor:

```xml
<field name="created" type="\Cake\I18n\DateTime" factory="parse"/>
```

Check the factory's accepted input and serialized output together. A custom value object can implement `PhpCollective\Dto\Dto\FromArrayToArrayInterface` to define array conversion. Field-level serialization settings are listed in the XSD; use a format that the target object can reconstruct.

Custom traits can add methods without editing generated files. Use the DTO-level `traits` attribute with the fully qualified trait name. Mark a DTO or field with `deprecated="Use its replacement"` to add a generated deprecation annotation.

## Other definition engines

Set `CakeDto.engine` to one of these classes:

| Engine | Files | Additional dependency |
| --- | --- | --- |
| `PhpCollective\Dto\Engine\XmlEngine` | `.xml` | DOM and libxml extensions |
| `PhpCollective\Dto\Engine\PhpEngine` | `.php` | None |
| `PhpCollective\Dto\Engine\YamlEngine` | `.yml` | PHP YAML extension |
| `PhpCollective\Dto\Engine\NeonEngine` | `.neon` | `nette/neon` |

For example, select the PHP engine in `config/bootstrap.php`:

```php
use Cake\Core\Configure;
use PhpCollective\Dto\Engine\PhpEngine;

Configure::write('CakeDto.engine', PhpEngine::class);
```

Create `config/dto.php`:

```php
<?php
use PhpCollective\Dto\Config\Schema;
use PhpCollective\Dto\Config\Dto;
use PhpCollective\Dto\Config\Field;

return Schema::create()
    ->dto(Dto::create('Product')->fields(
        Field::string('title'),
        Field::int('stockCount')->default(0),
    ))
    ->toArray();
```

Generation uses the same command for every engine. The initializer creates XML only. Sample definitions for each engine are in [docs/examples](https://github.com/dereuromark/cakephp-dto/tree/master/docs/examples).
