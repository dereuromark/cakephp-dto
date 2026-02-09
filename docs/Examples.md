## Examples

### Mutable
Using [basic.dto.xml](examples/basic.dto.xml).

In these cases the DTO is just an explicit transport tool, and it is OK/expected that the DTO changes over its lifetime,
and code will set, add, remove from it. Values are also often expected to be not set.

Most importantly: Copies of such an entity will cause side-effects to the original(s). This fact of mutability must be known
to you as programmer to avoid bugs in your software.

```php
$ownerDto = new OwnerDto();
$ownerDto->setName('The Owner');

$carDto = new CarDto();
$carDto->setOwner($ownerDto);

$otherCarDto = $carDto;

// A trivial example
$otherCarDto->getOwner()->setName('The new owner');

// You might not expect the original $carDto to also change it's value... Hopefully you do :)
$this->assertSame('The new owner', $otherCarDto->getOwner()->getName());
$this->assertSame('The new owner', $carDto->getOwner()->getName());
```

### Immutable
Using [immutable.dto.xml](examples/immutable.dto.xml).

In these cases we want to make sure, the DTO, once created, is not changing anymore that easily.
Required values are for sure set. Modifications create a new object, keeping the previous one unchanged for further use with that dataset.

```php
$array = [
    'id' => 2,
    'author' => [
        'id' => 1,
        'name' => 'me'
    ],
    'title' => 'My title',
    'created' => new FrozenTime(time() - DAY),
];

$articleDto = new ArticleDto($array);
$this->assertInstanceOf(AbstractImmutableDto::class, $articleDto);

// A trivial example
$modifiedArticleDto = $articleDto->withTitle('My new title');
$this->assertSame('My new title', $modifiedArticleDto->getTitle());
$this->assertSame('My title', $articleDto->getTitle());

// A reason why we want to use immutable datetime objects (FrozenTime):
$created = $articleDto->getCreated();
$isToday = $created->addDay()->isToday();
// A mutable datetime inside $articleDto->getCreated() would now accidentally be modified
$this->assertTrue($isToday);

// But luckily we don't have this side effect with our immutable one.
$this->assertSame($created, $articleDto->getCreated());
```

### ORM Entity to DTO
Using [orm.dto.xml](examples/orm.dto.xml) and immutable DTOs matching the entities (including primary keys, but without foreign keys).

This is not so much that it should be done, but that it can be done :)
This would be much more valuable (and necessary) for active record pattern ORMs (maybe someone can provide a Laravel example?^^).

Let's first get a record from the DB, including its relations:
```php
$articleEntity = $this->Articles->find()->contain(['Author', 'Tags')
    ->all()->toArray();
```

The data will most likely look like (this is from the OrmTest.php test case run):
```php
object(TestApp\Model\Entity\Article) {
    'id' => (int) 2,
    'author' => object(TestApp\Model\Entity\Author) {
        'id' => (int) 1,
        'name' => 'me',
        ...
    
    },
    'title' => 'My title',
    'created' => object(Cake\I18n\FrozenTime) {
        ...
    },
    'tags' => [
        object(TestApp\Model\Entity\Tag) {

            'id' => (int) 3,
            'name' => 'Awesome',
            ...
        },
        object(TestApp\Model\Entity\Tag) {
            'id' => (int) 4,
            'name' => 'Shiny',
            ...
        }
    ],
    ...
}
```

Now let's put it into our DTO (including the relations):
```php
$articleDto = new ArticleDto($articleEntity->toArray(), true);
```
We use the 2nd param (`ignoreMissing`) as `true` to allow fields to be present in the array that are not part of the DTO.
Those will then just get lost (foreign keys like `author_id` for example, since we already got it in `Author.id`).

Let's then just toArray() that one to verify:
```php
$result = $articleDto->touchedToArray();
```

And this is the result (you can verify it in the test case):
```php
[
    'id' => 2,
    'author' => [
        'id' => 1,
        'name' => 'me'
    ],
    'title' => 'My title',
    'tags' => [
        [
            'id' => 3,
            'name' => 'Awesome'
        ],
        [
            'id' => 4,
            'name' => 'Shiny'
        ]
    ]
];
```

Since the view layer (or other parts of the system that only need the read-only data from your entities) 
does not need to care about the state of the entities (persisted yes/no, errors yes/no, ...), you can easily break it down to these simple
containers to be read in the template:
```
echo h($articleDto->getTitle());
```

And you also then know: Based on the fact that required fields in this (immutable) entity must be there, this method cannot return an empty value.

### Mapping helpers

You can also use the mapper helpers to keep entity-to-DTO conversion and pagination output consistent.

```php
use CakeDto\Http\DtoJsonResponse;
use CakeDto\Mapper\DtoMapper;
use TestApp\Dto\ArticleDto;

$article = $this->Articles->get(1, contain: ['Authors', 'Tags']);
$articleDto = DtoMapper::fromEntity($article, ArticleDto::class, ignoreMissing: true);

$articles = $this->Articles->find()->contain(['Authors', 'Tags'])->all();
$articleDtos = DtoMapper::fromIterable($articles, ArticleDto::class, ignoreMissing: true);

$paged = $this->paginate($this->Articles->find()->contain(['Authors', 'Tags']));
$paging = $this->request->getAttribute('paging');
$pagination = DtoMapper::fromPaginated($paged, $paging, 'Articles', ArticleDto::class, ignoreMissing: true);

return DtoJsonResponse::fromPagination($pagination);
```
