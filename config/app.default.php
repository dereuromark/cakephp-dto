<?php

return [
	'CakeDto' => [
		'format' => 'xml', // or your custom yml, neon, ...
		'strictTypes' => false, // This can create casting requirement
		'scalarTypeHints' => true,
		'immutable' => false, // This can have a negative performance impact
		'defaultCollectionType' => null, // Defaults to \ArrayObject
		'debug' => false, // Add all meta data into DTOs for debugging
	],

];
