<?php

namespace CakeDto;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Routing\RouteBuilder;
use CakeDto\Command\DtoGenerateCommand;
use CakeDto\Command\DtoInitCommand;

/**
 * Plugin for CakeDto
 */
class CakeDtoPlugin extends BasePlugin {

	/**
	 * @var bool
	 */
	protected bool $bootstrapEnabled = false;

	/**
	 * @var bool
	 */
	protected bool $middlewareEnabled = false;

	/**
	 * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
	 *
	 * @return void
	 */
	public function routes(RouteBuilder $routes): void {
		$routes->prefix('Admin', function (RouteBuilder $routes): void {
			$routes->plugin('CakeDto', function (RouteBuilder $routes): void {
				$routes->connect('/', ['controller' => 'CakeDto', 'action' => 'index']);

				$routes->fallbacks();
			});
		});
	}

	/**
	 * Define the console commands for an application.
	 *
	 * @param \Cake\Console\CommandCollection $commands The CommandCollection to add commands into.
	 * @return \Cake\Console\CommandCollection The updated collection.
	 */
	public function console(CommandCollection $commands): CommandCollection {
		$commands->add('dto init', DtoInitCommand::class);
		$commands->add('dto generate', DtoGenerateCommand::class);

		return $commands;
	}

}
