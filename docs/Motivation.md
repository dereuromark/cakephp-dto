## Motivation and Background

All of you sure had this problem before:
Working with more complex and nested arrays and then, in the template, not knowing exactly what the possible keys are and the nesting structure looks like.

There is also no IDE autocomplete or typehinting here, unless you are inside the same code piece that created it and use a new version of e.g. PHPStorm.

Using simple (associative) arrays there is basically almost no verification of the fields and their types.
PHPStan and other introspection tools can also then not work well with it.

So what is the solution? Usually [DTOs](https://dzone.com/articles/practical-php/practical-php-patterns-data) are the best approach here.
It can be somewhat cumbersome to create those, though.

Some people argue that arrays are faster and use less memory than objects.
This might have been true back in PHP 5.2 and before - maybe. But it sure isn't anymore for all cases. Let's look into performance comparisons later.

### Other existing solutions
The PHP DTO ecosystem has evolved significantly with PHP 8.x features. Here's the current landscape (2024/2025):

**Active Libraries:**
- [spatie/laravel-data](https://github.com/spatie/laravel-data): Laravel-specific, runtime reflection with PHP 8 attributes. Features validation, TypeScript generation, and API resource integration.
- [cuyz/valinor](https://github.com/CuyZ/Valinor): Framework-agnostic runtime mapper with PHPStan/Psalm type support (generics, shaped arrays). Excellent error messages.
- [symfony/serializer](https://symfony.com/doc/current/serializer.html): Component-based serialization for Symfony. Supports JSON, XML, YAML, CSV.
- [jms/serializer](https://github.com/schmittjoh/serializer): Mature annotation-driven serializer with versioning and Doctrine integration.

**Deprecated:**
- [spatie/data-transfer-object](https://github.com/spatie/data-transfer-object): **Deprecated as of 2023**. Maintainers recommend `spatie/laravel-data` or `cuyz/valinor`.

**Outdated**
- [phpgears/dto](https://github.com/phpgears/dto): Using annotations, including immutability
- [yago-o/simple-dto](https://github.com/YagO-o/simple-dto): Very simplistic annotation approach
- [dto/dto](https://github.com/fireproofsocks/dto): Using the JSON Schema standard
- [tiagobutzke/json2dto](https://github.com/tiagobutzke/json2dto): A basic JSON to DTO converter

**Native PHP 8.2+ (No Library):**
```php
final readonly class UserDto
{
    public function __construct(
        public int $id,
        public string $email,
    ) {}
}
```
Sufficient for simple cases, but lacks collections, validation, and inflection support.

**Common issues with runtime libraries:**
- Runtime reflection overhead on every instantiation
- IDE support limited by "magic" - autocomplete depends on plugin quality
- Static analysis requires additional annotations or plugins

### Why generated code?
This plugin takes a fundamentally different approach: **code generation instead of runtime reflection**.

Other libraries leverage declared properties and reflection/introspection at runtime to finalize the DTO.
What if we let a generator do that for us? Taking the maximum performance benefit from creating a customized object,
while having all the addons we want on top for free?

We generate optimized DTOs where all inflection, reflection, validation and asserting is done at generation time.
Using them is just as simple as with basic arrays, only with tons of benefits on top.

**Key advantages of code generation:**
- **Zero runtime reflection** - no performance overhead per instantiation
- **Excellent IDE support** - real methods mean perfect autocomplete and "Find Usages"
- **Perfect static analysis** - PHPStan/Psalm work without plugins or annotations
- **Reviewable code** - generated classes can be inspected in pull requests
- **No magic** - what you see is exactly what runs

### Comparison with alternatives

| Aspect                  |   cakephp-dto   |    laravel-data    |     valinor     | symfony  | native PHP |
|-------------------------|:---------------:|:------------------:|:---------------:|:--------:|:----------:|
| **Approach**            | Code generation | Runtime reflection | Runtime mapping | Runtime  |   Manual   |
| **IDE Autocomplete**    |    Excellent    |        Good        |      Good       |   Good   | Excellent  |
| **Static Analysis**     |    Excellent    |        Good        |    Excellent    |   Good   | Excellent  |
| **Runtime Performance** |      Best       |      Moderate      |    Moderate     | Moderate |    Best    |
| **Validation**          |      Basic      |        Full        |      Good       | Partial  |    None    |
| **TypeScript Gen**      |       No        |        Yes         |       No        |    No    |     No     |
| **Collections**         |    Built-in     |      Built-in      |    Built-in     |  Manual  |   Manual   |
| **Inflection**          |    Built-in     |       Manual       |     Manual      |  Manual  |   Manual   |
| **Framework**           |     CakePHP     |      Laravel       |       Any       | Symfony  |    Any     |

**When to choose cakephp-dto:**
- You're building a CakePHP application
- Performance is important (API responses, batch processing)
- You want the best possible IDE and static analysis support
- You prefer configuration files over code attributes
- You need either mutable and immutable DTOs
- You work with different key formats (camelCase, snake_case, dashed)

### Summary

**Strengths vs competition:**

| Aspect              | cakephp-dto                | Others            |
|---------------------|----------------------------|-------------------|
| IDE/Static Analysis | Excellent (generated code) | Good (reflection) |
| Runtime Performance | Best (no reflection)       | Moderate          |
| Code Review         | Generated code visible     | Magic/runtime     |
| Inflection Support  | Built-in                   | Usually manual    |

**Gaps to address:**

| Feature        | cakephp-dto | laravel-data | valinor |
|----------------|-------------|--------------|---------|
| TypeScript Gen | No          | Yes          | No      |
| Validation     | Basic       | Full         | Good    |
| Generics       | No          | Partial      | Yes     |
| Union Types    | Limited     | Yes          | Yes     |

**Verdict:** cakephp-dto is the **only code-generation approach** in the PHP DTO ecosystem, giving it unique advantages for performance and IDE support. The main opportunities are adding TypeScript generation and better validation to match laravel-data's feature set.

### Why not entities

We could just bake more entities. But those are, as their namespace suggests, linked to their Table classes, the ORM and to actual rows in the DB tables.
They have stuff (like dirty/persisted) we don't need need for basic value objects. They have a not so clean property access we don't want either.
The more speaking and precise, the better.

The DTOs can have more use-case specific behavior and properties:
- Stricter types and type-handling, including classes/interfaces.
- Clean nullable vs null return value handling

Also see [this ticket](https://github.com/cakephp/cakephp/issues/11792) for details on some of the limitations of current entities.

### Why not immutable by default?

Arrays are also somewhat immutable here, so this is a fair point.
The goal was to first make it work for easy use cases, and simple usage.
For most use cases it is a good compromise with mutable objects - allowing to modify it easily where needed.

Immutable means that we either have to insert all into constructor or provide `with...()` methods.
This should be a deliberate choice.

### Why Dto suffix?

A `Post` or `Article` object will most likely clash with existing entities or alike. Having to alias in all files is not nice.
Also imagine `Date` and other reserved words.
So `PostDto` etc will be easy enough to avoid the issues while not being much longer than without suffix.

Inside code it can be also helpful to keep the prefixes in variables to avoid confusing or artificially creative variable names:
```php
$postArray = [
    'title' => 'My cool Post',
];
$postEntity = $this->Posts->newEntity($postArray);

$postDto = new PostDto($postEntity->toArray());
$postDto = $this->doSomething($postDto);
```
This makes the code also more readable in pull requests or when not directly inside the IDE (and typehinting/coloring).

### Why no interfaces?
Contracting with interfaces is important when building SOLID code.
For generated classes it seems like overhead.
From a stability perspective, manually modified code shouldnt extend/implement fluently changing generated one.
The generated classes always have to be evaluated as a whole.

We could introduce generated interfaces to be used as typehints, but the gain would be minimal.

### Sync with value objects

[Value objects](https://codete.com/blog/value-objects/) should also work nicely here.
We already have `DateTime` and alike in the core directly, but you might also want to work with
- Money VO to avoid float issues
- Custom use cases like [Paint](https://dzone.com/articles/practical-php-patterns/basic/practical-php-patterns-value) VO or [these](https://github.com/2dotstwice/valueobjects/tree/master/src).

Those value objects usually are immutable by design. This is very important to avoid side-effects here.
So `fromArray()` and `toArray()` should work nicely together with such VOs and will also be usable more cleanly with DTOs than arrays.

The key difference of value objects is that they can contain logic, transformers and "operations" between each other (`$moneyOne->substract($moneyTwo)`).
Whereas a DTO must not contain anything beyond holding pure data, and the setting/getting part.

### Performance benchmark
It is interesting to check the runtime and memory impacts of array vs DTO.

My findings are:
Yeah, depending on the use case arrays can be twice as fast, but at the same time memory usually remains the same or even decreases with objects.
One can say that if you do not use millions of DTO operations per request, the benefits clearly outweigh a possible speed disadvantage.
The code behaves more correctly and can be tested and verified more easily, especially after refactoring.

So after all, **developer speed, code readability and code reliability strongly increase with only a bit of speed decrease** that usually doesn't matter much for a normal web request.

Overuse of (complex) collections sure slows things down further. Especially with immutable objects, try to use simple arrays for it where possible.

If you want to replicate, check out the tests/benchmark/DtoBenchmarkShell and re-run it.
