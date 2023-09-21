<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\I18n\Date;
use Cake\TestSuite\TestCase;
use CakeDto\Dto\AbstractImmutableDto;
use InvalidArgumentException;
use TestApp\Dto\ArticleDto;

class RequiredTest extends TestCase {

	/**
	 * @return void
	 */
	public function testCreate() {
		$array = [
			'id' => 2,
			'author' => [
				'id' => 1,
				'name' => 'me',
			],
			'title' => 'My title',
			'created' => (new Date())->subDays(1),
		];

		$articleDto = new ArticleDto($array);
		$this->assertInstanceOf(AbstractImmutableDto::class, $articleDto);
	}

	/**
	 * @return void
	 */
	public function testCreateInvalidTypes() {
		$array = [
			'id' => 2,
			'author' => [
				'id' => 1,
				'name' => 'me',
			],
			'title' => ['My title'], // should be string
			'created' => (new Date())->subDays(1),
		];

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Type of field `title` is `array`, expected `string`.');

		new ArticleDto($array);
	}

}
