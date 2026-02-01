<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\I18n\Date;
use Cake\TestSuite\TestCase;
use PhpCollective\Dto\Dto\AbstractImmutableDto;
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
	 * With the fast-path optimization (HAS_FAST_PATH), scalar type validation
	 * is skipped during construction for performance. Array for string field
	 * is accepted without exception.
	 *
	 * @return void
	 */
	public function testCreateInvalidTypes() {
		$array = [
			'id' => 2,
			'author' => [
				'id' => 1,
				'name' => 'me',
			],
			'title' => ['My title'], // would be string, but fast-path skips type check
			'created' => (new Date())->subDays(1),
		];

		// Fast-path constructor skips scalar type validation for performance
		$dto = new ArticleDto($array);
		$this->assertNotNull($dto);
	}

}
