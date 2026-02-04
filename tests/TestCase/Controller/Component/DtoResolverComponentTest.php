<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use CakeDto\Controller\Component\DtoResolverComponent;
use TestApp\Controller\DtoResolverComponentController;
use TestApp\Dto\PageDto;

class DtoResolverComponentTest extends TestCase {

	/**
	 * @return void
	 */
	public function testResolvesDtoFromQuery(): void {
		$request = new ServerRequest([
			'params' => ['action' => 'create'],
			'query' => ['number' => 2, 'content' => 'Hello'],
		]);

		$controller = new DtoResolverComponentController($request);
		$registry = new ComponentRegistry($controller);
		$component = new DtoResolverComponent($registry);

		$component->beforeFilter(new Event('Controller.beforeFilter', $controller));

		$dto = $controller->getRequest()->getAttribute('page');

		$this->assertInstanceOf(PageDto::class, $dto);
		$this->assertSame(2, $dto->getNumber());
		$this->assertSame('Hello', $dto->getContent());
	}

}
