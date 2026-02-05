<?php

declare(strict_types=1);

namespace CakeDto\Application;

use Cake\Http\BaseApplication;
use CakeDto\Controller\DtoControllerFactoryTrait;

abstract class DtoApplication extends BaseApplication {

	use DtoControllerFactoryTrait;

}
