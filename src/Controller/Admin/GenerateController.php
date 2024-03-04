<?php

namespace CakeDto\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use CakeDto\Importer\Importer;

class GenerateController extends AppController {

	/**
	 * @param \Cake\Event\EventInterface $event
	 *
	 * @return void
	 */
	public function beforeFilter(EventInterface $event) {
		if ($this->components()->has('Auth') && method_exists($this->components()->get('Auth'), 'allow')) {
			$this->components()->get('Auth')->allow();
		} elseif ($this->components()->has('Authentication') && method_exists($this->components()->get('Authentication'), 'addUnauthenticatedActions')) {
			$this->components()->get('Authentication')->addUnauthenticatedActions(['display']);
		}
		if ($this->components()->has('Authorization') && method_exists($this->components()->get('Authorization'), 'skipAuthorization')) {
			$this->components()->get('Authorization')->skipAuthorization();
		}
	}

	/**
	 * @return void
	 */
	public function index(): void {
	}

	/**
	 * @return void
	 */
	public function schema(): void {
		if ($this->request->is(['post', 'put'])) {
			if ($this->request->getData('dto')) {
				$options = [
					'namespace' => $this->request->getData('namespace'),
				];
				$result = (new Importer())->buildSchema($this->request->getData('dto'), $options);
				$this->set(compact('result'));
			} else {
				$json = $this->request->getData('input');
				$type = $this->request->getData('type');

				$options = [
					'type' => $type,
					'namespace' => $this->request->getData('namespace'),
				];
				$schema = (new Importer())->parse($json, $options);

				$this->set(compact('schema'));
			}
		}

	}

}
