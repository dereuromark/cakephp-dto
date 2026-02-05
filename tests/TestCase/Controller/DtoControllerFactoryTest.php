<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase\Controller;

use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use CakeDto\Controller\DtoControllerFactory;
use TestApp\Application;
use TestApp\Controller\DtoResolverController;

class DtoControllerFactoryTest extends TestCase {

	/**
	 * @return void
	 */
	public function testResolvesDtoFromSignature(): void {
		$request = new ServerRequest([
			'params' => ['controller' => 'DtoResolver', 'action' => 'create'],
			'query' => ['number' => 2],
		]);

		$controller = new DtoResolverController($request);
		$factory = new DtoControllerFactory((new Application(CONFIG))->getContainer());

		$response = $factory->invoke($controller);

		$this->assertSame('2', (string)$response->getBody());
	}

}
