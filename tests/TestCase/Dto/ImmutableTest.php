<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\I18n\Date;
use Cake\TestSuite\TestCase;
use TestApp\Dto\ArticleDto;
use TestApp\Dto\BookDto;
use TestApp\Dto\PageDto;

class ImmutableTest extends TestCase {

	/**
	 * Tests that arrays passed to immutable DTOs are defensively copied.
	 *
	 * @return void
	 */
	public function testImmutableDefensiveCopyForArrays(): void {
		$tagsArray = [
			['id' => 1, 'name' => 'php', 'weight' => 10],
			['id' => 2, 'name' => 'cakephp', 'weight' => 20],
		];

		$articleDto = new ArticleDto([
			'id' => 1,
			'author' => ['id' => 1, 'name' => 'John'],
			'title' => 'Test Article',
			'created' => new Date(),
			'tags' => $tagsArray,
		]);

		// Modify the original array after creating the DTO
		$tagsArray[0]['name'] = 'modified';

		// The DTO should not be affected by external modification
		$dtoTags = $articleDto->getTags();
		$this->assertSame('php', $dtoTags[0]->getName());
	}

	/**
	 * Tests that nested array data is defensively copied.
	 *
	 * @return void
	 */
	public function testImmutableDefensiveCopyForNestedArrayData(): void {
		$tagData = ['id' => 1, 'name' => 'Original', 'weight' => 10];
		$tagsArray = [$tagData];

		$articleDto = new ArticleDto([
			'id' => 1,
			'author' => ['id' => 1, 'name' => 'John'],
			'title' => 'Test Article',
			'created' => new Date(),
			'tags' => $tagsArray,
		]);

		// Modify the original array after creating the ArticleDto
		$tagsArray[0]['name'] = 'Modified';

		// The DTO's internal copy should not be affected
		$dtoTags = $articleDto->getTags();
		$this->assertSame('Original', $dtoTags[0]->getName());
	}

	/**
	 * Tests immutable DTO with Collection built via methods.
	 *
	 * @return void
	 */
	public function testImmutableDefensiveCopyForCollectionViaMethod(): void {
		$page1 = new PageDto(['number' => 1, 'content' => 'Original Content']);

		// Build book using the withAddedPage method
		$bookDto = (new BookDto())->withAddedPage($page1);

		// Get the pages and verify content
		$pages = $bookDto->getPages()->toArray();
		$this->assertSame('Original Content', $pages[0]->getContent());
	}

	/**
	 * Tests immutable DTO constructor triggers defensive copy.
	 *
	 * @return void
	 */
	public function testImmutableConstructorDefensiveCopy(): void {
		$authorData = ['id' => 1, 'name' => 'John'];

		$articleDto = new ArticleDto([
			'id' => 1,
			'author' => $authorData,
			'title' => 'Test Article',
			'created' => new Date(),
		]);

		// Modify original array - should not affect DTO
		$authorData['name'] = 'Modified';

		// Verify DTO is unaffected
		$this->assertSame('John', $articleDto->getAuthor()->getName());
	}

}
