# DTO Plugin Documentation

First of all: This is not meant to replace every array, entity, ...
This should be used as a **conscious addition or replacement** for certain cluttered arrays or structures.
It should have a benefit in the cases where you apply it:
- Passing around a more explicit data container with clear content through a chain of objects/methods.
- Useful IDE benefits for the developer as well as static analyzing to spot errors and mistakes earlier.

If your current data structure can do all that already for you, don't apply a DTO on top :)
Only bloat what really brings a big plus on top.


## Chose your poison

You can choose between the following formats for the definitions:

- XML (.xml), native in CakePHP and default format (requires ext-libxml extension).
- YAML (.yml), native in PHP (requires ext-yaml extension).
- NEON (.neon), requires `nette/neon` package
- PHP (.php), with fluent builder API for full IDE support.

In Configure (via app.php), just set your desired engine:
```php
use PhpCollective\Dto\Engine\XmlEngine;
use PhpCollective\Dto\Engine\YamlEngine;
use PhpCollective\Dto\Engine\NeonEngine;
use PhpCollective\Dto\Engine\PhpEngine;

'CakeDto' => [
    'engine' => XmlEngine::class, // default
]
```

YAML or alike might have the advantage of less typing, but the power of XML comes with its XSD validation and full auto-complete/typehinting.
Just start typing and you will see how it already gives you all the options to chose from.

Tip: Check out the `examples/basic.dto.xml` and edit/add properties. Then you will see the power of such typehinting, how fast it is to modify.

### PHP Engine with Fluent Builder

The PHP engine allows you to define DTOs using a fluent builder API with full IDE autocomplete:

```php
<?php
// config/dto.php
use PhpCollective\Dto\Config\Schema;
use PhpCollective\Dto\Config\Dto;
use PhpCollective\Dto\Config\Field;

return Schema::create()
    ->dto(Dto::create('Car')->fields(
        Field::string('color'),
        Field::array('attributes', 'string'),
        Field::bool('isNew'),
        Field::int('distanceTravelled'),
        Field::float('value'),
        Field::class('manufactured', 'Cake\I18n\Date'),
        Field::dto('owner', 'Owner'),
    ))
    ->dto(Dto::create('Owner')->fields(
        Field::string('name')->required(),
        Field::int('birthYear'),
    ))
    ->dto(Dto::immutable('FlyingCar')->extends('Car')->fields(
        Field::int('maxAltitude')->required(),
    ))
    ->toArray();
```

**Available Field Types:**
- `Field::string('name')` - String field
- `Field::int('name')` - Integer field
- `Field::float('name')` - Float field
- `Field::bool('name')` - Boolean field
- `Field::array('name', 'elementType')` - Typed array (e.g., `string[]`)
- `Field::dto('name', 'DtoName')` - Reference to another DTO
- `Field::collection('name', 'ElementType')` - Collection with add methods
- `Field::class('name', 'ClassName')` - Any class (DateTimeImmutable, etc.)
- `Field::enum('name', 'EnumClass')` - PHP enum
- `Field::union('name', 'int', 'string')` - Union types
- `Field::mixed('name')` - Mixed type
- `Field::of('name', 'customType')` - Explicit type

**Field Modifiers:**
```php
Field::string('email')
    ->required()                    // Mark as required
    ->default('default@example.com') // Set default value
    ->deprecated('Use newEmail')    // Mark as deprecated
    ->factory('createFromString')   // Custom factory method
    ->serialize('string')           // Serialization mode

Field::collection('items', 'Item')
    ->singular('item')              // Singular name for addItem()
    ->associative()                 // Enable associative keys
    ->asCollection('\ArrayObject')  // Custom collection type
```

**DTO Modifiers:**
```php
Dto::create('User')
    ->asImmutable()                 // Make immutable
    ->extends('BaseUser')           // Extend another DTO
    ->deprecated('Use NewUser')     // Mark as deprecated
```


## Build your config

We first need to build our first DTO configs.
For this you can start creating an empty definitions file (`dto.xml` for default format):
```
bin/cake dto init
```
You should see this file now in your app's `config/` dir.

Tip: With `--plugin PluginName` (`-p`) you can initialize it for your plugins.

Let's add some basic DTOs now:

```xml
    <dto name="Car">
        <field name="color" type="string"/>
        <field name="attributes" type="string[]"/>
        <field name="isNew" type="bool"/>
        <field name="distanceTravelled" type="int"/>
        <field name="value" type="float"/>
        <field name="manufactured" type="\Cake\I18n\FrozenDate"/>
        <field name="owner" type="Owner"/>
    </dto>

    <dto name="Cars">
        <field name="cars" type="Car[]" collection="true" singular="car"/>
    </dto>

    <dto name="Owner">
        <field name="name" type="string"/>
        <field name="birthYear" type="int"/>
    </dto>

    <dto name="FlyingCar" extends="Car">
        <field name="maxAltitude" type="int"/>
    </dto>
```

Thanks to the XSD file you can fully autocomplete it, so almost no typing here. It will also show you invalid attributes in red.

If you want, you can also use a `config/dto/` folder and then separate config files within.
Those have to have the pattern `*.dto.xml` then to be found.

## Generate your DTOs
```
bin/cake dto generate
```

You should now see the generated CarDto and CarsDto classes in `src/Dto/`.

Use them anywhere as
```php
use App\Dto\CarDto;
use App\Dto\CarsDto;

$carDto = new CarDto();

$carDto->setColor('black');
$distanceTravelled = $carDto->getDistanceTravelled(); // int|null
$isNew = $carDto->getIsNewOrFail(); // bool (must be set)

$carsDto = new CarsDto();
$carsDto->addCar($carDto);
```

You can use `--confirm` (`-c`) to verify if the generated files are valid (PHP syntax check).

Using `--plugin PluginName` (`-p`) you can generate them for a plugin and its definition.

Some generated examples can be found in `tests/test_app/src/Dto/` in Github.

### Available types
```xml
<field ... type="..."/>
```

Simple scalars and types:
- `int`
- `float`
- `string`
- `bool`
- `callable`
- `resource`
- `iterable`
- `object`
- `mixed`

Simple array types:
- `array` without array type, but no further annotation (assumes "mixed" type)
- `...[]` with array type and concrete annotation ("mixed" is not allowed here)

Concrete objects:
- DTOs (without suffix)
- Value objects and custom classes using FQCN and leading `\`.
- Enums (mainly int/string backed enums)

For all of the above you will have setters and getters available.

Collections (require `type="array"` or `type="...[]"`):
- `collection="true"` as `\ArrayObject` (recommended)
- `collectionType="array"` as `array`
- `collectionType="\Cake\Collection\Collection"` as custom collection type.

For collections you will also have `add{SingularName}()` methods as convenience wrappers available to add to the existing stack.
So here it is important to either manually define `singular="..."`, or make sure it can be auto-singularized (it tries to inflect the singular from the plural name).

**Singular Collision Detection:** The generator will throw an exception if the singular name collides with an existing field name. For example:
```xml
<field name="item" type="string"/>
<field name="items" type="string[]" singular="item"/> <!-- Error: collision with 'item' field -->
```
This prevents silent failures where `addItem()` would conflict with `getItem()`/`setItem()`.

### Namespaces
If you plan on using DTOs a lot, it is advised to namespace them. Instead of a long flat collection of DTOs, you can nest them in subfolders.
This also prevents you from having to be more and more creative - as class names must be unique per namespace.

The namespace is defined as prefix, ending with a slash (and could contain multiple levels even):
```xml
<dto name="MyForest/MyTree">
```
This will generate a DTO `MyTreeDto` inside `src/Dto/MyForest/` then.

### Extending DTOs

You can extend an existing DTO to add additional fields for custom use cases:
```xml
<dto name="FlyingCar" extends="Car">
```
You can add fields on top. You must not change already existing parent field attributes, however.

### Default Value and Nullable
Sometimes it can be handy to define a default value for a field.
```xml
defaultValue="0"
```
Based on the type of field, it will transform/cast values accordingly:
- int, float: Simple cast
- bool: string `true`/`false` become boolean values.

Default values must be simple scalar values.
They automatically make non-required fields required (and not nullable) this way, unless specified differently:
```xml
defaultValue="0" required="false"
```
This similar to DB and e.g. not nullable integer columns with `0` as default value.

`null` is not a default value, but set via boolean `required` key independently of this and means you can set or get `null` as value.
Since PHP7.1+ this will have not an effect on default value behavior,
whereas in versions before it would actually (due to the language restriction) set a default value as `null` here
if a param/return type is used and no default value is provided:
```php
    /**
     * @param \Cake\I18n\FrozenDate|null $manufactured
     *
     * @return $this
     */
    public function setManufactured(\Cake\I18n\FrozenDate $manufactured = null) {}
```
This technically means that you could just call the method as `->setManufactured()` and it would set it to `null` - the only way to allow nullable here.
It will be a clean API with non-optional first parameter as expected then:
```php
    /**
     * @param \Cake\I18n\FrozenDate|null $manufactured
     *
     * @return $this
     */
    public function setManufactured(?\Cake\I18n\FrozenDate $manufactured) {}
```
An argument is always required, even for setting it to null: `->setManufactured(null)`


### Union types
In some cases you need to declare union types, e.g. scalar `string|int|float` or array `string[]|int[]`.

```xml
<field name="identifier" type="string|int"/>
<field name="values" type="string[]|int[]"/>
```

**Native PHP Union Type Hints (PHP 8.0+):**
For simple union types like `string|int`, the generator now produces native PHP union type hints:
```php
public function setIdentifier(string|int $identifier): static
public function getIdentifier(): string|int|null
```

**Nullable Union Types:**
Nullable union types use the `type|null` syntax rather than the `?type` shorthand:
```php
public function getValue(): string|int|null
```

**Array Union Types:**
Array notation in union types (e.g., `string[]|int[]`) cannot use native PHP type hints since `string[]|int[]` is not valid PHP syntax. These will use docblock annotations only:
```php
/**
 * @param array<string>|array<int> $values
 */
public function setValues(array $values): static
```

### Field Mapping (mapFrom/mapTo)
When working with external APIs or databases, field names often differ from your DTO property names.
Use `mapFrom` and `mapTo` to define custom mappings:

```xml
<field name="emailAddress" type="string" mapFrom="email" mapTo="email_address"/>
```

**mapFrom:** Maps an input key to the field during `fromArray()`:
```php
// Input array uses 'email', but DTO field is 'emailAddress'
$dto = new UserDto(['email' => 'john@example.com'], true);
$dto->getEmailAddress(); // 'john@example.com'
```

**mapTo:** Maps the field to an output key during `toArray()`:
```php
$dto->setEmailAddress('john@example.com');
$dto->toArray(); // ['email_address' => 'john@example.com']
```

This is especially useful when integrating with APIs that use different naming conventions than your internal DTOs.

### Custom Traits
You can add custom traits to your generated DTOs using the `traits` attribute:

```xml
<dto name="Article" traits="\App\Dto\Traits\TimestampTrait,\App\Dto\Traits\SlugTrait">
    <field name="title" type="string"/>
</dto>
```

The traits will be added to the generated DTO class, allowing you to share common functionality across multiple DTOs.

### Serialize Mode
Control how fields are serialized using the `serialize` attribute:

```xml
<field name="password" type="string" serialize="hidden"/>
<field name="createdAt" type="\DateTime" serialize="string"/>
```

Available modes:
- `hidden` - Excludes the field from serialization output
- `string` - Forces string representation during serialization

### Deprecations
You can add `deprecated="Reason why"` to any DTO or a specific field of it. It will mark the methods as strike-through in your IDE.


## Usage

### Get vs Get\*OrFail
If you want to retrieve a field that could also be not set (nullable), use the normal getter.
But if you want to make sure (without additional guard coding) that a value to be used is existing (`!== null`), use
`get{Field}OrFail()` here.
Those are also allowed to be chained then, as they would exit with the correct exception message being thrown:
```php
echo $fooDto->getBarOrFail()->getBazOrFail()->getIntField();
```

Tools like PHPStan understand the important difference here and warn you if you try to pass possible null values into other methods that do not expect or allow this.

### Set vs Set\*OrFail
Use the orFail wrapper here if you want to have early warning system (also through PHPStan) regarding null wrongly being set. Otherwise use normal setter.
```php
$footDto->setBar($valueCanBeNull);
$footDto->setBarOrFail($valueCanNotBeNull);
```

### Has
Using `has{Field}()` you can check on the existence of a value, basically `!== null` for fields.
Collections, however, cannot be null, they always return something. This means that for those the method returns true for `count > 0`.

### Read
Using `read()` you can use a path array to quickly access deeply nested values.
This is especially useful if any of the elements in the chain can be null. Usually you have to check for each level separately:
```php
// Nothing can be null in between, typed as int
$green = $carsDto->getCarOrFail('one')->getColorOrFail()->getGreenOrFail();

// Something can be null in between, typed as int|null
$greenOrNull = null;
if ($carDto->hasCar('one')) {
    $carDto = $carsDto->getCar('one');
    if ($carDto->hasColor()) {
        $color = $carDto->getColor();
        $greenOrNull = $color->getGreen();
    }
}

// Typed as mixed|null
$greenOrNull = $carsDto->read(['cars', 'one', 'color', 'green']);
```

The downside is that you lose the return type-hinting which is usually useful for static analysis.
It should also be avoided where not needed - as you lose the auto-complete/type-hinting aspect.
Using the class constants as field names can however compensite a bit:
```php
$path = [$carsDto::FIELD_CARS, 'one', CarDto::FIELD_COLOR, 'green'];
$greenOrNull = $carsDto->read($path);
```
The associative key doesn't have a class constant, neither does the key of the value object.

### FromArray/ToArray
These methods allow to transport data from array to DTO, the other way around or between DTOs.
Using `touchedToArray()` you can also just shift over the fields that have been set.

```php
$myDto->fromArray($otherDto->touchedToArray());
// Add other fields specific to this DTO
$myDto->set...
```

Use the 2nd param (`ignoreMissing`) as `true` to allow fields to be present in the incoming array that are not part of the DTO.
```php
$articleDto = new ArticleDto($array, true);
```
Those will then just get lost (foreign keys like `author_id` for example, since we already got it in `Author.id`).
That avoids you having to unset those before creating the DTO. It also makes it easier to miss (new) array fields that should be part of the DTO, however.

If you only want specific fields, you can pass it as 2nd arg:
```php
$otherDto->toArray($otherDto::TYPE_DEFAULT, ['list', 'of', 'fields]);
```

Objects, which are not DTOs or Collections, can get lost here.
If you have value objects (especially immutable ones), implement the `FromArrayToArrayInterface` here:

```php
use PhpCollective\Dto\Dto\FromArrayToArrayInterface;

final class Paint implements FromArrayToArrayInterface {}
```

When using other value objects, like DateTime, it will expect the constructor to re-map the serialized output.
So the following should work out of the box:
```xml
<field name="lastLogin" type="\Cake\I18n\FrozenTime"/>
```
In this case any incoming string, e.g. in serialized (JSON) format `2011-01-26T19:01:12Z`, would
also become an object right away.

Sometimes, especially with more custom serializing, you might need a factory:
```xml
<field name="lastLogin" type="\Cake\I18n\FrozenTime" factory="createFromArray"/>
```

This would make the `fromArray` part auto convert using the object's `createFromArray()` method as factory if needed:

If you have more complex factory needs, you can map this to a static class method:
```xml
<field name="lastLogin" type="\My\Custom\Time" factory="\Some\Class::fromString"/>
```
Using this setup, you should always have the same DTO back, no matter how often
you transform it to array or serialized form.

Note: Make sure that for whatever you use the type matches the serialized form.

### Static Factory Methods
DTOs provide convenient static factory methods for instantiation:

#### createFromArray()
Create a DTO from an associative array:
```php
$carDto = CarDto::createFromArray([
    'color' => 'red',
    'isNew' => true,
]);
```

The second parameter `ignoreMissing` allows extra array keys that aren't defined in the DTO:
```php
$carDto = CarDto::createFromArray($data, true);
```

The third parameter allows you to specify the array key inflection type:
```php
$carDto = CarDto::createFromArray($data, true, CarDto::TYPE_UNDERSCORED);
```

#### create()
An alias for `createFromArray()` that accepts an optional array:
```php
$carDto = CarDto::create(); // Empty DTO
$carDto = CarDto::create(['color' => 'blue']);
$carDto = CarDto::create($data, true);
```

#### fromUnserialized()
Create a DTO from a serialized string:
```php
$serialized = serialize($carDto);
$newCarDto = CarDto::fromUnserialized($serialized);
```

These static methods are particularly useful when you want to avoid using `new` directly or when working with dependency injection containers.

### Fields and touched fields
You can introspect DTOs to get information about their fields:

```php
// Get all field names defined in the DTO
$allFields = $carDto->fields(); // ['color', 'isNew', 'value', 'owner', ...]

// Get only fields that have been set or modified
$modifiedFields = $carDto->touchedFields(); // ['color', 'isNew']

// Use with toArray to export only touched fields
$partialArray = $carDto->touchedToArray();
```

This is useful for partial updates, dirty checking, or debugging which fields have been populated.

### Inflection usage

The DTOs can work with **different inflections out of the box** (`my_field` from DB or form input, `my-field` from URL query strings, `myField` from options arrays).
That is super useful when working with associative arrays that are getting passed around in an app in different ways.

Let's look at a query string coming into a controller:
```php
$myDto = new MyDto();

// manual assignment
$myDto->setMinValue((int)$this->request->getQuery('min-value'));
...

// or a bit shorter for multiple query strings:
$myDto->fromArray($this->request->getQuery(), true, MyDto::TYPE_DASHED);

$this->MyComponent->doSomething($myDto);

// inside the component I now know exactly what fields I can use
public function doSomething(MyDto $myDto) {
    $myField = $myDto->getMyField();
    ...
}
```

In a similar way you could fetch the fields of POST/DB data and then use them more speaking:
```php
$article = new ArticleDto();
$article->fromArray($this->request->getData(), false, $article::TYPE_UNDERSCORED);

// Now we can work with it nicely
$title = $article->getTitle();
if ($article->getAbbreviation()) {
    $title .= ' (' . $article->getAbbreviation() . ')';
}
```

### Controller DTO Resolution (Method Signatures)

Use the controller factory trait in your application to enable DTO parameter resolution (or extend `CakeDto\Application\DtoApplication`):

```php
use CakeDto\Controller\DtoControllerFactoryTrait;

class Application extends BaseApplication {
	use DtoControllerFactoryTrait;
}
```

Then annotate action parameters with `#[MapRequestDto]`:

```php
use CakeDto\Attribute\MapRequestDto;
use App\Dto\UserDto;

public function add(#[MapRequestDto(source: MapRequestDto::SOURCE_BODY)] UserDto $dto): void {
}
```

Sources: `body`, `query`, `request`, or `auto` (default).

### Controller DTO Resolution (Request Attributes)

If you prefer request attributes, load the resolver component in your `AppController`:

```php
public function initialize(): void {
	parent::initialize();

	$this->loadComponent('CakeDto.DtoResolver');
}
```

Use `#[MapRequestDto]` on the action method:

```php
use CakeDto\Attribute\MapRequestDto;
use App\Dto\UserDto;

#[MapRequestDto(UserDto::class, source: MapRequestDto::SOURCE_BODY, name: 'user')]
public function add(): void {
	/** @var \App\Dto\UserDto $dto */
	$dto = $this->getRequest()->getAttribute('user');
}
```

### Custom Collections
By default, working with collections can look like this:
```php
$carsDto = new CarsDto();

echo count($carsDto->getCars()); // outputs 0
$carsDto->addCar($carDtoOne);
$carsDto->addCar($carDtoTwo);
echo count($carsDto->getCars()); // outputs 2

$carsDto->setCars($carsCollection);
```

When working with a custom collection, it probably needs its own templates.
For `\Cake\Collection\Collection` as collection type it has one out of the box, and could look like this:

```php
$carsDto = new CarsDto();

echo $carsDto->getCars()->count(); // outputs 0
$carsDto->addCar($carDtoOne);
$carsDto->addCar($carDtoTwo);
echo $carsDto->getCars()->count(); // outputs 2

$carsDto->setCars($carsCollection);
```

### Associative collections
The attribute `associative="true"` will automatically assume a basic collection. Basic as in `array` or `\ArrayObject`.
Other collections most likely will not work.

```xml
<dto name="Foo">
    <field name="bars" type="Bar[]" singular="bar" collection="true" associative="true"/>
    <field name="bazs" type="array" singular="baz" associative="true"/>
</dto>

<dto name="Bar">
    <!-- anything -->
</dto>
```

```php
// Example for adding DTOs with associated keys
$fooDto->addBar('a', new Bar());
$fooDto->addBar('b', new Bar());

// Example for setting DTO with associated keys
$bars = new \ArrayObject([
    'a' => new Bar(),
    'b' => new Bar(),
]);
$fooDto->setBars($bars);

// Example for adding associated array items
$fooDto->addBaz('x', 'X');
$fooDto->addBaz('y', 'Y');

// Example for setting associated array
$fooDto->setBazs([
    'x' => 'X',
    'y' => 'Y',
]);

// Example for getting associated items
$fooDto->getBar('a'); // returns the associated Dto object to "a"
$fooDto->getBar('c'); // throws exception because it doesn't exists

// Example for checking associated items
$fooDto->hasBar('a'); // returns true
$fooDto->hasBar('c'); // returns false
```

#### fromArray() and key option
When importing from an array, sometimes these arrays are not yet associative. Instead of having to manually do that, you can use `key="myKey"` to do that when creating the DTO.
The collection type must be array though, and the incoming data type, as well.
It can then use the key on the data to set the key for the collection element:
```json
 "labels": [
    {
      "name": "bug",
      "description": "Something isn't working",
      "color": "f29513"
    }
  ],
```
will transform into the DTO field as associative array collection:
```php
'labels' => [
    'bug' => ... {
        'name' => 'bug',
        'color' => 'f29513',
        ...
    }
],
```

### Collections and nullable elements
Sometimes you can have associative collections that can also contain null values for their keys.
In this case you can define them using `?` prefix:
```xml
<field name="elements" type="?int[]" singular="element" associative="true"/>
```
Note: This is not valid for union types.

### Deep Cloning with clone()
When working with nested DTOs, a shallow copy (using PHP's `clone`) shares references to nested objects.
Use the `clone()` method for deep copying:

```php
$original = new CarDto([
    'color' => 'red',
    'owner' => new OwnerDto(['name' => 'John']),
]);

// Shallow copy - nested owner is shared
$shallow = clone $original;
$shallow->getOwner()->setName('Jane'); // Also changes $original!

// Deep copy - nested owner is independent
$deep = $original->clone();
$deep->getOwner()->setName('Jane'); // Only changes $deep
```

The `clone()` method recursively clones:
- Nested DTOs
- Arrays containing DTOs
- ArrayObject collections
- Other Traversable collections
- Any other objects (using native clone)

### serialize() and unserialize()
These methods should be used carefully, for security reasons.
Make sure none of the values are dangerous objects. Best to use only for scalar values.

**Security validation:** The `__unserialize()` method validates that no unknown fields are present in the serialized data. This prevents injection of unexpected data during unserialization.

### Property access
In some cases it can be easier to use `->get('myField')` or `->myField` access.
Especially with programmatic access to the DTO you will find this easier than manually inflecting.
```php
$field = 'myField';
$value = $dto->$field;
```
The advantage of the property access here is to retain full return-type-hinting.

In case you only have the underscored or dashed version, you need to call `field()` first.
```php
$field = $dto->field('my_field'); // returns 'myField'
$dto->set($field, $value);
```

#### Generic set() Method (Mutable DTOs)
For mutable DTOs, you can use the generic `set()` method for programmatic field assignment:
```php
$dto->set('color', 'red');
$dto->set('is_new', true, $dto::TYPE_UNDERSCORED);
```

This is particularly useful when field names are determined at runtime or when working with inflected keys.

#### Generic with() Method (Immutable DTOs)
For immutable DTOs, the generic `with()` method allows programmatic updates:
```php
$updatedDto = $dto->with('color', 'blue');
$updatedDto = $dto->with('is_new', true, $dto::TYPE_UNDERSCORED);
```

While generated `withColor()` methods are available, the generic `with()` is useful for dynamic field updates.

### PHP Template Usage
Inside templates just annotate the variables passed down in the doc block at the top:
```php
/**
 * @var \App\View\AppView $this
 * @var \App\Dto\MyDto $myDto
 * ...
 */
```
This way you have full typehinting power here also in the templates.


### Twig Template Usage
Twig nicely supports such DTOs and getters and their usage in templates.
For `{{ article.abbreviation }}` It will automatically do a lookup on the `ArticleDto::getAbbreviation()` method.
Check the [TwigView](https://github.com/WyriHaximus/TwigView) plugin which is a dependency of Bake and Dto plugin anyway already.


## Immutability
Immutable objects can make life simpler in many cases.
They are especially applicable for value types, where objects don't have an identity so they can be easily replaced.

`immutable="true"` on the DTO makes it read only and provides `->with{Field}()` instead of setters.
The original DTO will not be changed, but a cloned copy is returned instead.

```php
$carDto = new CarDto([
    'color' => 'black',
    'isNew' => false,
]);
$carDto = $carDto->withDistanceTravelled(10000);
```
(Re)assignment is important to have the changes applied.

### Required fields
It also means we could set some fields as required now from the start as usually that is the primary way to create a DTO now.
Required fields (`required="true"`) can not be nullable, and would throw an exception if not provided or empty.
You can still use `with...()` methods to overwrite fields.

In this case there are also no `getOrFail()` methods and `get...()` is directly typed as not nullable.

```php
use App\Dto\CarDto;

$carDto = new CarDto([
    'isNew' => false, // required field
]);
$carDto = $carDto->withDistanceTravelled(10000); // optional field

$distanceTravelled = $carDto->getDistanceTravelled(); // still int|null
$isNew = $carDto->getIsNew(); // bool (must be set)
```

Note that you can set a default value to avoid the exception on create if the field would otherwise remain `null`.
```
<field name="count" type="int" required="true" defaultValue="0"/>
```
In this case it becomes optional again in a way that for creating a non-null value can, but doesn't have to be set - and it can never be `null`.

### Use Cases and Performance
Note that while side-effects are usually reduced, immutability, especially on often changing objects can cause some performance decreases.
So best not to overuse except for critical use cases or when you really only create and then only read from a DTO.

It also can either solve or create accidental side effects on nested DTOs or object in general, especially collections.

Immutability should be used on simple DTOs and ideally always with `array` collections only.
At the same time that means: If you want mutable collections, do not use `array`, but `\ArrayObject`.

**Defensive Array Copying:** When creating an immutable DTO from an array, a defensive copy is made to prevent external modification of the source array from affecting the DTO's internal state.


## Configuration
You can set some defaults via `app.php` and global Configure settings:

```php
return [
    'Dto' => [
        'strictTypes' => false, // This can require additional casting
        'scalarAndReturnTypes' => true,
        'typedConstants' => false, // Requires PHP 8.3+ for typed class constants
        'immutable' => false, // This can have a negative performance impact
        'defaultCollectionType' => null, // Defaults to \ArrayObject
    ],
];
```

### Runtime Configuration

You can also configure DTOs at runtime using static methods:

#### Default Key Type
Set a global default key type for all DTOs, useful when your application consistently uses one format:
```php
use PhpCollective\Dto\Dto\AbstractDto;

// In your bootstrap.php or Application.php
AbstractDto::setDefaultKeyType(AbstractDto::TYPE_UNDERSCORED);

// Now all DTOs will default to underscored keys
$dto = new MyDto($data); // Uses TYPE_UNDERSCORED automatically
$array = $dto->toArray(); // Returns underscored keys
```

#### Custom Collection Factory
Set a custom factory for creating collections, useful for integrating with framework-specific collection classes:
```php
use PhpCollective\Dto\Dto\AbstractDto;
use Cake\Collection\Collection;

// Use CakePHP's Collection class for all DTO collections
AbstractDto::setCollectionFactory(fn($items) => new Collection($items));
```


## Exclude Generated DTOs from Static Analysis

Generated code usually shouldn't run through code-style or static analysis checks.

### PHP_CodeSniffer

Add an exclude pattern to your `phpcs.xml`:

```xml
<rule ref="...">
    <exclude-pattern>src/Dto/*</exclude-pattern>
</rule>
```

### PHPStan

Add an exclude path to your `phpstan.neon`:

```yaml
parameters:
    excludePaths:
        - src/Dto/
```

Alternatively, you can avoid exclusions altogether by generating DTOs into a separate directory outside `src/` (e.g. `generated/`). Set the output path in your `app.php` config:

```php
'CakeDto' => [
    'srcPath' => ROOT . DS . 'generated' . DS,
],
```

Then add a PSR-4 autoload entry in your `composer.json`:

```json
{
    "autoload": {
        "psr-4": {
            "App\\Dto\\": "generated/"
        }
    }
}
```

See the base package's [SeparatingGeneratedCode.md](https://github.com/php-collective/dto/blob/master/docs/SeparatingGeneratedCode.md) for details.


## Validate in CI
You can validate your currently generated DTOs in CI or via pre-commit hook.
For this use the `dry-run` (`-d`) option:
```
bin/cake dto generate -d
```
The expected result is `0` (all good);

If the error code is `2`, there are some changes detected, and the files need to be (re)generated.
Error code `1` is bad and basically means that the definitions are invalid. The error output should give some details here.

Tip: Use `--verbose` (`-v`) to see a diff of what's changing.


## Composer Scripts

You can add convenience scripts to your `composer.json`:

```json
{
    "scripts": {
        "dto:generate": "bin/cake dto generate",
        "dto:check": "bin/cake dto generate -d"
    }
}
```


## Version Control
You can either `.gitignore` the `src/Dto` folder, or you can simple commit them into version control and just "update commit" changes.
If you generate them always on the fly, make sure that they are also generated for CI and deployment.
And if you commit them, use the `-d` param to verify on CI or staging that the files are up to date.

Note: If you are committing the DTO class files, you can use this plugin as `require-dev` dependency.
In this case you don't need to generate anything for deploy.


## Entities
Are entities not needed anymore now?
No, do not see DTOs as replacement for entities. Entities are linked to a row in the DB table, they have a state (persisted true/false) which DTOs do not.
Rather see them as counterpart wherever you abused entities to at runtime "add more fields" then actually exist on the DB row (which are not even annotated then on the entity).
Create a speaking DTO to contain what you need.
Same goes for any array you might have made up on the fly.

You should be able to easily transform between DTO/Entity using `...->fromArray(...->toArray())`.
See the examples for details.


## Scalar Param and Return Types
Types for scalars are added by default. Use Configure and `'CakeDto.scalarAndReturnTypes'` set to  `false` to disable this.


## Strict Types
You can let the script generate the `declare(strict_types=1)` part into the top of the PHP files.
`'CakeDto.strictTypes` set to  `true` will enable this.

This will also stop auto-casting then. Used together with the scalar type hints you should make sure that the data
you store in your DTOs meets those standards.

I would rather recommend leaving this off and instead using the scalar type hints only.

## Typed Constants
For projects using PHP 8.3+, you can enable typed class constants instead of docblock annotations:

`'CakeDto.typedConstants'` set to `true` will enable this feature.

**Default behavior (PHP 8.2+ compatible):**
```php
/**
 * @var string
 */
public const FIELD_ID = 'id';
```

**With typed constants enabled (PHP 8.3+ required):**
```php
public const string FIELD_ID = 'id';
```

This provides the same type safety while using modern PHP syntax and eliminating the need for docblock annotations on constants.

## Suffix

The suffix for your classes defaults to `Dto`.
You can modify or remove the suffix using `CakeDto.suffix` config.

Use empty string for none.
But keep in mind: With this certain [reserved](https://www.php.net/manual/en/reserved.php) words (Object, ...) cannot be used as DTO (class) names anymore then.

## Debugging

You can use `debug($dto)` to introspect your DTO. You will get an array like so:

```php
object(TestApp\Dto\ArticleDto) {
    'data' => [
        ...
    ],
    'touched' => [
        ...
    ],
    'extends' => 'CakeDto\Dto\AbstractImmutableDto',
    'immutable' => true
}
```


## Examples
See [Examples](Examples.md) for basic, immutable and complex entity use cases.

## Generating from JSON
See [Generating from JSON](Generating.md)

## Validation Bridge

The `DtoValidator` class bridges DTO validation rules into CakePHP's `Validator`, allowing you to
validate request data against the rules defined in your DTOs.

### Usage

```php
use CakeDto\Validation\DtoValidator;
use App\Dto\UserDto;

$validator = DtoValidator::fromDto(new UserDto());
$errors = $validator->validate($this->request->getData());
if ($errors) {
    // Handle validation errors
}
```

The validation rules come from the DTO's `validationRules()` method (provided by the core dto library).
For example, a DTO might define:

```php
public function validationRules(): array {
    return [
        'name' => ['required' => true, 'minLength' => 2, 'maxLength' => 50],
        'email' => ['pattern' => '/^[^@]+@[^@]+$/'],
        'age' => ['min' => 0, 'max' => 150],
    ];
}
```

### Rule Mappings

| DTO Rule    | CakePHP Validator Method              |
|-------------|---------------------------------------|
| `required`  | `requirePresence()` + `notEmptyString()` |
| `minLength` | `minLength()`                         |
| `maxLength` | `maxLength()`                         |
| `min`       | `greaterThanOrEqual()`                |
| `max`       | `lessThanOrEqual()`                   |
| `pattern`   | `regex()`                             |

## TODOs

See https://github.com/dereuromark/cakephp-dto/wiki and open issues.
