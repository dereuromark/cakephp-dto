<?php

return [
	'CakeDto' => [
		'engine' => null, // Defaults to `XmlEngine`. Can be your custom yml, neon, ... engine
		'suffix' => 'Dto', // Class name suffix (recommended)
		'strictTypes' => false, // This can create casting requirement
		'scalarAndReturnTypes' => true,
		'immutable' => false, // This can have a negative performance impact
		'defaultCollectionType' => null, // Defaults to `\ArrayObject`
		'debug' => false, // Add all meta data into DTOs for debugging
	],

];
