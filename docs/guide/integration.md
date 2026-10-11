# CakePHP integration

These examples use generated DTOs whose fields match the data being mapped. DTOs carry data; your ORM tables still load and save entities.

## Map entities

```php
use App\Dto\ProductDto;
use CakeDto\Mapper\DtoMapper;

$entity = $this->fetchTable('Products')->get($id);
$product = DtoMapper::fromEntity($entity, ProductDto::class, true);
```

The third argument discards entity fields that the DTO does not declare. Without it, extra fields throw. Pass a key format when entity field names differ from the DTO's camelCase names:

```php
$product = DtoMapper::fromEntity(
    $entity,
    ProductDto::class,
    true,
    ProductDto::TYPE_UNDERSCORED,
);
```

The mapper reads the entity's visible fields. It preserves object values such as dates and decimal value objects, and converts nested entities to arrays so the DTO library can rebuild declared nested DTOs. Hidden fields are excluded.

Map a result set or an array with `fromIterable()`:

```php
$entities = $this->fetchTable('Products')->find()->all();
$products = DtoMapper::fromIterable($entities, ProductDto::class, true, ProductDto::TYPE_UNDERSCORED);
```

It returns an indexed array of DTOs, not a lazy query. Existing DTO items must be instances of the requested class.

## JSON responses

Return the response from a controller action:

```php
use CakeDto\Http\DtoJsonResponse;

return DtoJsonResponse::fromDto($product);
```

`fromCollection($products)` produces a JSON array. Both methods accept an HTTP status and optional headers. Encoding errors throw `JsonException`; they do not silently return an empty body.

## Pagination

```php
use App\Dto\ProductDto;
use CakeDto\Mapper\DtoMapper;
use CakeDto\Http\DtoJsonResponse;

$entities = $this->paginate($this->fetchTable('Products'));
$paging = ['Products' => $entities->pagingParams()];
$pagination = DtoMapper::fromPaginated(
    $entities,
    $paging,
    'Products',
    ProductDto::class,
    true,
    ProductDto::TYPE_UNDERSCORED,
);

return DtoJsonResponse::fromPagination($pagination);
```

The response contains `data` and `meta`. Metadata includes `page`, `perPage`, `count`, `pageCount`, `current`, `hasNext` and `hasPrev`. The alias must match the paging entry. Use the paginated result's `pagingParams()`; CakePHP 5 does not set a request `paging` attribute. This mapper requires numeric pagination with total counts; count-free simple pagination is not supported. Missing metadata throws `InvalidArgumentException`. `getItems()` and `getMeta()` expose the mapped values separately.

## Validate input

```php
use CakeDto\Validation\DtoValidator;

$validator = DtoValidator::fromDto($product);
$errors = $validator->validate($this->request->getData());
```

The bridge reads `validationRules()` from the DTO and maps these rules:

| DTO rule | CakePHP validation |
| --- | --- |
| `required` | Presence plus `notEmptyString()` |
| `minLength` | Minimum length |
| `maxLength` | Maximum length |
| `min` | Greater than or equal |
| `max` | Less than or equal |
| `pattern` | Regular expression |

The bridge does not add type validation for every declared field or convert incoming key formats. Additional rules can be supplied by a custom DTO trait overriding `validationRules()` or by extending the returned CakePHP validator. Required DTO construction and input validation are separate steps; an empty DTO with required fields cannot be constructed merely to obtain its validator.

## Request parameters

CakePHP 5.4 introduced the core `#[RequestToDto]` parameter attribute. Earlier CakePHP 5 releases need explicit mapping with `createFromArray()` inside the action. The plugin no longer provides its former request-resolution attribute or application trait.

```php
use App\Dto\ProductDto;
use Cake\Controller\Attribute\RequestToDto;
use Cake\Http\Response;
use CakeDto\Http\DtoJsonResponse;

public function preview(#[RequestToDto] ProductDto $product): Response {
    return DtoJsonResponse::fromDto($product);
}
```

The core resolver calls `createFromArray()` on the DTO. Its default input source depends on the request method, and it does not enable `ignoreMissing` for you. Keep request fields compatible with the DTO definition. See the [CakePHP 5.4 release notes](https://bakery.cakephp.org/2026/07/19/cakephp_540.html) and [attribute implementation](https://github.com/cakephp/cakephp/blob/5.x/src/Controller/Attribute/RequestToDto.php).
