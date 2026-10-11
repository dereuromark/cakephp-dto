# Collections

Declare a collection when a field contains multiple nested DTOs:

```xml
<dtos xmlns="cakephp-dto">
    <dto name="Product">
        <field name="title" type="string"/>
    </dto>
    <dto name="Catalog">
        <field name="products" type="Product[]" collection="true" singular="product"/>
    </dto>
</dtos>
```

The default collection type is `\ArrayObject`. The field starts as an empty collection:

```php
use App\Dto\CatalogDto;
use App\Dto\ProductDto;

$catalog = new CatalogDto();
$catalog->addProduct(new ProductDto(['title' => 'Notebook']));

$count = count($catalog->getProducts()); // 1
$hasProducts = $catalog->hasProducts(); // true
```

`singular="product"` determines the generated item method's name. `hasProducts()` tests whether the collection contains items, rather than whether a nullable scalar is set.

## Associative collections

```xml
<field name="products" type="Product[]" collection="true" singular="product" associative="true"/>
```

The item methods now take a string key:

```php
$catalog->addProduct('notebook', new ProductDto(['title' => 'Notebook']));
$product = $catalog->getProduct('notebook');
$exists = $catalog->hasProduct('notebook');
```

`getProduct()` throws for an unknown key; check `hasProduct()` when absence is expected.

To derive keys from an imported item's field, add `key="title"` or another string field:

```xml
<field name="products" type="Product[]" collection="true" singular="product" associative="true" key="title"/>
```

Choose keys that are unique for the input. Duplicate keys can replace earlier entries. The configured `CakeDto.assocKeyFields` list affects schema inference from example JSON, not every collection's runtime key.

## Array and CakePHP collection types

Use `collectionType="array"` for a PHP array or a fully qualified supported collection class:

```xml
<field name="products" type="Product[]" collection="true" singular="product" collectionType="\Cake\Collection\Collection"/>
```

Plugin loading registers the CakePHP collection adapter. Its generated append methods keep the new collection returned by `appendItem()`, because CakePHP collections do not mutate in place.

Use `CakeDto.defaultCollectionType` to select the default for generated fields without their own `collectionType`. Setting a default requires regenerating the classes. Custom collection classes need an adapter and compatible conversion behavior; declaring an arbitrary class name does not make its API supported.

Immutable DTOs expose `withAdded...()` methods instead of changing the original DTO. Keep the returned DTO, and choose collection and item types that fit the immutability you need.
