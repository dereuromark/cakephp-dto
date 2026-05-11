<?php

use Cake\Http\ServerRequest;

// The following configs can be globally configured, copy the array content over to your ROOT/config/app.php

return [
	'CakeDto' => [
		'engine' => null, // Defaults to `XmlEngine`. Can be your custom yml, neon, ... engine
		'suffix' => 'Dto', // Class name suffix (recommended)
		'srcPath' => null, // Defaults to `SRC`, e.g. `ROOT . DS . 'generated' . DS` for separated generated code
		'strictTypes' => false, // This can create casting requirement
		'scalarAndReturnTypes' => true,
		'typedConstants' => false, // Requires PHP 8.3+ for typed class constants
		'immutable' => false, // This can have a negative performance impact
		'defaultCollectionType' => null, // Defaults to `\ArrayObject`
		'debug' => false, // Add all meta data into DTOs for debugging
		'keyType' => null, // Dto::TYPE_DEFAULT by default which uses Dto::TYPE_CAMEL
		'assocKeyFields' => ['slug', 'login', 'name'], // Candidate keys when inferring associative collections from example JSON
		'databaseTypeMap' => [
			// `decimal` intentionally defaults to `string` in the importer to avoid precision loss.
			// Uncomment to restore the pre-2.10 behavior if your generated DTOs still expect float:
			// 'decimal' => 'float',
			// Or opt into a project-specific value object type, e.g. for cakephp-decimal:
			// 'decimal' => '\PhpCollective\DecimalObject\Decimal',

			// `json` stays `array` by default for generation-output BC in the current release line.
			// Uncomment to opt into schema-honest JSON typing instead:
			// 'json' => 'mixed',
		],
		'finder' => null, // Defaults to the php-collective/dto Finder class
		'builder' => null, // Defaults to `CakeDto\Generator\Builder`
		'generator' => null, // Defaults to `CakeDto\Generator\Generator`
		'renderer' => null, // Defaults to `CakeDto\View\Renderer`
		'adminAccess' => function (ServerRequest $request): bool {
			// Default-deny gate for /admin/cake-dto/generate. Must return literal `true` to grant access.
			$identity = $request->getAttribute('identity');

			return $identity !== null && in_array('admin', (array)$identity->roles, true);
		},
	],
];
