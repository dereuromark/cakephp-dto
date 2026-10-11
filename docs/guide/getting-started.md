# Getting started

Complete [Installation](/guide/installation) first. This example creates a mutable `App\Dto\ProductDto` with two fields.

## 1. Create the definition

```sh
bin/cake dto init
```

The command creates `config/dto.xml`. Replace its contents with:

```xml
<?xml version="1.0"?>
<dtos xmlns="cakephp-dto">
    <dto name="Product">
        <field name="title" type="string"/>
        <field name="stockCount" type="int" defaultValue="0"/>
    </dto>
</dtos>
```

`title` starts as `null`; `stockCount` starts as `0`. The default application namespace is `App`. If your application uses another namespace, the generator reads `App.namespace`.

## 2. Generate the PHP class

```sh
bin/cake dto generate --confirm
```

This writes `src/Dto/ProductDto.php` and checks its PHP syntax. Edit the definition and regenerate when adding fields. The generator owns `src/Dto/`: it overwrites changed classes and deletes generated classes whose definitions were removed. Keep handwritten classes in another directory.

## 3. Use the DTO

```php
use App\Dto\ProductDto;

$product = new ProductDto();
$product->setTitle('Notebook');
$product->setStockCount(3);

$title = $product->getTitleOrFail(); // 'Notebook'
$stockCount = $product->getStockCount(); // 3
$data = $product->toArray(); // ['title' => 'Notebook', 'stockCount' => 3]
```

Use `getTitle()` when an unset value is allowed. Use `getTitleOrFail()` when the next operation needs a value; it throws if the field is `null`.

You can also construct the DTO from an array:

```php
$product = ProductDto::createFromArray([
    'title' => 'Notebook',
    'stockCount' => 3,
]);
```

DTO field names use camelCase. For underscored input, supply the key format explicitly:

```php
$product = ProductDto::createFromArray(
    ['title' => 'Notebook', 'stock_count' => 3],
    false,
    ProductDto::TYPE_UNDERSCORED,
);
```

## 4. Check generated files in CI

```sh
bin/cake dto generate --dry-run
```

An exit status of `0` means the files match their definitions. Status `2` means generation would change files; run the generator and commit the results. A dry run does not write files or create output directories.

Next, read [Definitions](/guide/definitions) for required fields and immutable DTOs, or [CakePHP integration](/guide/integration) for mapping an entity and returning JSON.
