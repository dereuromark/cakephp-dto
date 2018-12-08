<?php

return [
	'CakeDto' => [
		'format' => 'xml', // or your custom yml, neon, ...
		'strictTypes' => false, // Requires PHP 7.1+
		'scalarTypeHints' => false, // null => Auto-detect, requires PHP 7.1+
		'immutable' => false, // This can have a negative performance impact
		'defaultCollectionType' => null, // Defaults to \ArrayObject
		'debug' => false, // Add all meta data into DTOs for debugging
	],

];
