<?php

use Cake\Http\ServerRequest;

// The following configs can be globally configured, copy the array content over to your ROOT/config/app.php

return [
	'CakeDto' => [
		'engine' => null, // Defaults to `XmlEngine`. Can be your custom yml, neon, ... engine
		'suffix' => 'Dto', // Class name suffix (recommended)
		'strictTypes' => false, // This can create casting requirement
		'scalarAndReturnTypes' => true,
		'typedConstants' => false, // Requires PHP 8.3+ for typed class constants
		'immutable' => false, // This can have a negative performance impact
		'defaultCollectionType' => null, // Defaults to `\ArrayObject`
		'debug' => false, // Add all meta data into DTOs for debugging
		'keyType' => null, // Dto::TYPE_DEFAULT by default which uses Dto::TYPE_CAMEL
		'adminAccess' => function (ServerRequest $request): bool {
			// Default-deny gate for /admin/cake-dto/generate. Must return literal `true` to grant access.
			$identity = $request->getAttribute('identity');

			return $identity !== null && in_array('admin', (array)$identity->roles, true);
		},
	],
];
