<?php
declare(strict_types=1);

use PhpCollective\Dto\Config\Dto;
use PhpCollective\Dto\Config\Field;
use PhpCollective\Dto\Config\Schema;

return Schema::create()
	->dto(Dto::immutable('Transaction')->fields(
		Field::dto('customerAccount', 'CustomerAccount')->required(),
		Field::float('value')->required(),
		Field::string('comment'),
		Field::class('created', 'Cake\I18n\Date')->required(),
	))
	->dto(Dto::create('CustomerAccount')->fields(
		Field::string('customerName')->required(),
		Field::int('birthYear'),
		Field::class('lastLogin', 'Cake\I18n\DateTime'),
	))
	->toArray();
