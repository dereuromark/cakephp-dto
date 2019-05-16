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
I was looking into other open source PHP packages that can be found on GitHub:

- [spatie/data-transfer-object](https://github.com/spatie/data-transfer-object): Using reflection and properties  
- [phpgears/dto](https://github.com/phpgears/dto): Using annotations, including immutability
- [yago-o/simple-dto](https://github.com/YagO-o/simple-dto): Very simplistic annotation approach
- [dto/dto](https://github.com/fireproofsocks/dto): Using the JSON Schema standard 
- [tiagobutzke/json2dto](https://github.com/tiagobutzke/json2dto): A basic JSON to DTO converter

They all face similar issues:
Manual work to generate or slow due to reflection, almost completely unsupportive for IDEs and static analyzers.

### Why generated code?
These libraries all seem to leverage declared properties and reflection/introspection at runtime to finalize the DTO.
What if we would let a generator do that for us? Taking the maximum performance benefit we can get from creating a customized object, 
while having all the addons we want on top for free?

We can just generate us the optimized DTOs. All inflection, reflection, validation and asserting is all done after generating.  
So using them is just as simple as with basic arrays, only with tons of benefits on top.

The single most important benefits you get with these generated DTOs:
- You can, with your IDE, **check for usage of a method in your whole code base** now. Rightclick -> Find usages.
- Generated classes, version-controlled or on-the-fly, are easier for humans to verify and easier for tools like PHPStan to use for static analyzing.

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
