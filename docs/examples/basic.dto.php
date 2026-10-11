<?php
use PhpCollective\Dto\Config\Schema;
use PhpCollective\Dto\Config\Dto;
use PhpCollective\Dto\Config\Field;

return Schema::create()
    ->dto(Dto::create('Product')->fields(
        Field::string('title'),
        Field::int('stockCount')->default(0),
    ))
    ->toArray();
