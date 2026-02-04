<?php

declare(strict_types=1);

namespace CakeDto\Controller;

use Cake\Http\ControllerFactoryInterface;

trait DtoControllerFactoryTrait {

	/**
	 * @return \Cake\Http\ControllerFactoryInterface
	 */
	public function controllerFactory(): ControllerFactoryInterface {
		return new DtoControllerFactory($this->getContainer());
	}

}
