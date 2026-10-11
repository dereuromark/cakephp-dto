# Using DTOs

The following examples assume a mutable `ProductDto` with nullable `title` and an integer `stockCount` field. Define and generate it in [Getting started](/guide/getting-started).

## Read and write fields

```php
use App\Dto\ProductDto;

$product = new ProductDto();
$product->setTitle('Notebook');

$title = $product->getTitle();
$hasTitle = $product->hasTitle();
$requiredTitle = $product->getTitleOrFail();
```

A normal getter can return `null` for an optional scalar field. Its `OrFail` variant throws if the value is absent. `setTitle()` accepts the nullable field type; `setTitleOrFail()` accepts a non-null value. Required fields have non-null getters.

An object assignment shares the same object. Use `clone $product` for PHP's normal shallow clone or `$product->clone()` for the DTO library's recursive clone of supported nested objects and collections. Mutable objects inside an immutable DTO are still mutable objects.

## Array conversion

```php
$product = ProductDto::createFromArray([
    'title' => 'Notebook',
    'stockCount' => 3,
]);

$product->fromArray(['stockCount' => 4]);
$data = $product->toArray();
$changedFields = $product->touchedFields();
$partialData = $product->touchedToArray();
```

`createFromArray()` creates a DTO; `fromArray()` imports into an existing mutable DTO. Unknown input fields throw by default. Set the second argument, `ignoreMissing`, to `true` to discard them:

```php
$product = ProductDto::createFromArray($incomingData, true);
```

`ProductDto::create()` creates an empty DTO, and `ProductDto::create($data)` is a shorter factory for array input. Required fields still apply to both factories.

Use `fields()` for all declared field names. Touched fields track assignment, not a database comparison; DTOs do not track persisted records.

To export selected fields:

```php
$data = $product->toArray(ProductDto::TYPE_DEFAULT, ['title']);
```

## Key formats

Supply the format when importing underscored or dashed keys:

```php
$product = ProductDto::createFromArray(
    ['title' => 'Notebook', 'stock_count' => 3],
    false,
    ProductDto::TYPE_UNDERSCORED,
);

$data = $product->toArray(ProductDto::TYPE_UNDERSCORED);
```

Available formats include `TYPE_CAMEL`, `TYPE_UNDERSCORED` and `TYPE_DASHED`. Use field constants such as `ProductDto::FIELD_TITLE` when referring to a declared field in code. `read()` accepts a path through nested DTO data and returns an untyped value; generated getters retain more useful return types.

## Generic field access

Generated methods provide field-specific type declarations. The generic methods support dynamic field names:

```php
$product->set('title', 'Notebook');
$title = $product->read(['title']);
$title = $product->title;
```

Immutable DTOs use `with('title', 'Notebook')`; keep the returned object. Property reads use the declared DTO field names. Use the generated methods when the field is known in advance.

## JSON and PHP serialization

`fromUnserialized()` reads a JSON string, not PHP's `serialize()` format:

```php
$json = json_encode($product->toArray(), JSON_THROW_ON_ERROR);
$copy = ProductDto::fromUnserialized($json);
```

PHP serialization is a separate operation:

```php
$serialized = serialize($product);
$copy = unserialize($serialized, ['allowed_classes' => [ProductDto::class]]);
```

Restrict allowed classes when reading serialized data. Nested DTOs or value objects need their own permitted classes. For HTTP output, use [DtoJsonResponse](/guide/integration#json-responses).

Object serialization and reconstruction depend on the object's definition, factory and serialization settings. Test a round trip for custom value objects; an arbitrary object's string representation is not enough to guarantee reconstruction.

## Templates

Read generated getters in PHP templates and escape text with CakePHP's `h()`:

```php
<?= h($product->getTitle()) ?>
```

With TwigView, getters can be called as methods:

```twig
{{ product.getTitle() }}
```

The plugin's internal code-generation renderer disables Twig autoescaping to produce PHP source. That setting belongs to code generation, not to your application's HTML templates.
