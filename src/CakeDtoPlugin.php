<?php

namespace CakeDto;

use Cake\Collection\Collection;
use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Routing\RouteBuilder;
use CakeDto\Command\DtoGenerateCommand;
use CakeDto\Command\DtoInitCommand;
use PhpCollective\Dto\Dto\Dto;
use PhpCollective\Dto\Engine\XmlValidator;

/**
 * Plugin for CakeDto
 */
class CakeDtoPlugin extends BasePlugin {

	/**
	 * @var bool
	 */
	protected bool $bootstrapEnabled = true;

	/**
	 * @var bool
	 */
	protected bool $middlewareEnabled = false;

	/**
	 * Plugin bootstrap.
	 *
	 * Configures the standalone library for CakePHP integration:
	 * - Sets XSD path for XML validation
	 * - Configures collection factory to use Cake\Collection\Collection
	 *
	 * @param \Cake\Core\PluginApplicationInterface $app Application instance
	 * @return void
	 */
	public function bootstrap(PluginApplicationInterface $app): void {
		parent::bootstrap($app);

		// Use CakePHP-DTO XSD for validation (different namespace than standalone)
		XmlValidator::setXsdPath($this->getPath() . 'config' . DIRECTORY_SEPARATOR . 'dto.xsd');

		// Configure Dto to use Cake\Collection\Collection for collections
		Dto::setCollectionFactory(fn ($items) => new Collection($items));
	}

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
