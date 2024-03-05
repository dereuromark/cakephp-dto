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
- Create your own one (PHP, custom format and schema validator, ...).

In Configure (via app.php), just set your desired engine:
```php
    'engine' => YamlEngine::class,
```

YAML or alike might have the advantage of less typing, but the power of XML comes with its XSD validation and full auto-complete/typehinting.
Just start typing and you will see how it already gives you all the options to chose from.

Tip: Check out the `examples/basic.dto.xml` and edit/add properties. Then you will see the power of such typehinting, how fast it is to modify.


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
In same rare cases you need to declare union types, e.g. scalar `string|int|float` or array `string[]|int[]`.
Note that this will usually prevent more strict typehinting to be possible.


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
use CakeDto\Dto\FromArrayToArrayInterface;

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

### Fields and touched fields
You can get a list of the DTOs fields using `fields()`.
`touchedFields()` will give you the list of not fields that have been set or unset so far.

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

### serialize() and unserialize()
These methods should be used carefully, for security reasons.
Make sure none of the values are dangerous objects. Best to use only for scalar values.

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


## Configuration
You can set some defaults via `app.php` and global Configure settings:

```php
return [
    'Dto' => [
        'strictTypes' => false, // This can require additional casting
        'scalarAndReturnTypes' => true,
        'immutable' => false, // This can have a negative performance impact
        'defaultCollectionType' => null, // Defaults to \ArrayObject
    ],
];
```


## Disable Code Style Check
Generated code usually shouldn't run through code-style checks.
Disable the folder by using `--ignore=/src/Dto/`.


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


## Version Control
You can either `.gitignore` the `src/Dto` folder, or you can simple commit them into version control and just "update commit" changes.
If you generate them always on the fly, make sure that they are also generated for CI and deployment.
And if you commit them, use the `-d` param to verify on CI or staging that the files are up to date.

Note: If you are commiting the DTOs, you can use this plugin as `require-dev` dependency. In this case you don't need to generate anything for deploy.


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

## TODOs

See https://github.com/dereuromark/cakephp-dto/wiki and open issues.
