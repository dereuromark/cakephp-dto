<?php

declare(strict_types=1);

namespace CakeDto\Controller;

use Cake\Controller\ControllerFactoryInterface;

trait DtoControllerFactoryTrait {

	/**
	 * @return \Cake\Controller\ControllerFactoryInterface
	 */
	public function controllerFactory(): ControllerFactoryInterface {
		return new DtoControllerFactory($this->getContainer());
	}

}
