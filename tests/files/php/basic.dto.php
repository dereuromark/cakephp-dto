<?php
declare(strict_types=1);

use PhpCollective\Dto\Config\Dto;
use PhpCollective\Dto\Config\Field;
use PhpCollective\Dto\Config\Schema;

return Schema::create()
	->dto(Dto::create('Car')->fields(
		Field::class('color', 'TestApp\ValueObject\Paint'),
		Field::bool('isNew'),
		Field::float('value'),
		Field::int('distanceTravelled'),
		Field::array('attributes', 'string'),
		Field::class('manufactured', 'Cake\I18n\Date'),
		Field::dto('owner', 'Owner'),
	))
	->dto(Dto::create('Cars')->fields(
		Field::collection('cars', 'Car')->associative(),
	))
	->dto(Dto::create('Owner')->fields(
		Field::string('name'),
		Field::int('birthYear'),
	))
	->dto(Dto::create('FlyingCar')->extends('Car')->fields(
		Field::int('maxAltitude')->default(0),
		Field::int('maxSpeed')->default(0)->required(),
		Field::array('complexAttributes'),
	))
	->dto(Dto::create('OldOne')->extends('Car')->deprecated('Yeah, sry')->fields(
		Field::string('name'),
	))
	->toArray();
