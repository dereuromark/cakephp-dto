# Importing schemas

The optional admin interface converts example JSON, JSON Schema or database columns into DTO definitions. It previews text for copying; it does not write definition files or generated PHP classes.

## Enable access

Importer pages deny access unless `CakeDto.adminAccess` is a Closure returning literal `true`. Configure a check matching your application's identity and access rules during bootstrap:

```php
use Cake\Core\Configure;
use Cake\Http\ServerRequest;

Configure::write('CakeDto.adminAccess', static function (ServerRequest $request): bool {
    $identity = $request->getAttribute('identity');

    return $identity !== null && $identity->get('is_admin') === true;
});
```

This example assumes an identity with a boolean `is_admin` field and a `get()` method. Adapt that check to your actual identity; signing in alone does not imply admin access.

An unset setting, a non-Closure, or a result other than literal `true` denies access with HTTP 403. Explicit HTTP exceptions from your callback keep their status; unexpected failures are logged without their exception message and become a generic 403.

The importer extends CakePHP's base `Controller`, independently of your `AppController` components and callbacks. Application middleware still runs. The gate skips the authorization policy check when the request has an authorization service; it supplies the access decision itself. Authentication must have made the identity available before `Controller.initialize`, when the gate runs.

Load plugin routes normally, then open `/admin/cake-dto` or `/admin/cake-dto/generate`. The pages use your application's layout and Flash elements. The interface includes schema and database tools.

## JSON and JSON Schema

Open `/admin/cake-dto/generate/schema`, paste input and select the input type. If no type is selected, the importer attempts detection.

Example data can suggest field types, nested DTOs and collections, but does not establish which fields are required. The initial top-level name may be `Object`; rename it before generation. Review inferred names, optional fields and collection keys against the full API contract.

JSON Schema can supply more detail, including required fields. Local `$ref` pointers such as `#/definitions/Product` are supported. External-file references need a base path that the web interface does not provide.

Choose PHP, YAML, XML or NEON on the result page, then copy the definition into a file matching your configured engine. The web interface produces XML with the plugin's `cakephp-dto` namespace. XML produced by the standalone library uses a different namespace and must be adapted before generation. Run `bin/cake dto generate --confirm` after saving the definition.

The `CakeDto.assocKeyFields` candidates default to `slug`, `login` and `name`. Review inferred collection keys for uniqueness and string type.

## Database columns

Open `/admin/cake-dto/generate/database` and select tables. The web tool uses the `default` connection; it does not expose a connection selector. The parser API accepts another connection name:

```php
use CakeDto\Importer\DatabaseParser;

$parser = new DatabaseParser();
$tables = $parser->listTables('reporting');
$definitions = $parser->parse(['products'], 'reporting');
```

Selected table columns become DTO fields. This is schema inference, not ORM association mapping or automatic construction of complete related-record DTOs.

Current mappings include `decimal` to `string`, `json` to `array`, `date` to `Cake\I18n\Date`, and datetime types to `Cake\I18n\DateTime`. Decimal strings preserve stored precision. JSON columns can contain scalar values as well as arrays; override that mapping if your data needs them:

```php
Configure::write('CakeDto.databaseTypeMap', [
    'json' => 'mixed',
]);
```

Projects using a decimal value object can map `decimal` to its fully qualified class name. Install the corresponding dependency and verify its construction and serialization before generating that field. Overriding `decimal` to `float` restores older generation output while accepting floating-point precision limits.
