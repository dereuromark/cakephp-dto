<?php

namespace CakeDto\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use CakeDto\Importer\DatabaseParser;
use Exception;
use PhpCollective\Dto\Importer\Importer;

class GenerateController extends AppController {

	/**
	 * @param \Cake\Event\EventInterface $event
	 *
	 * @return void
	 */
	public function beforeFilter(EventInterface $event): void {
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
				$format = $this->request->getData('format') ?: 'php';
				$options = [
					'namespace' => $this->request->getData('namespace'),
					'format' => $format,
				];
				$dto = $this->request->getData('dto');
				$namespace = $this->request->getData('namespace');
				$result = (new Importer())->buildSchema($dto, $options);
				$this->set(compact('result', 'dto', 'namespace', 'format'));
			} else {
				$json = $this->request->getData('input');
				$type = $this->request->getData('type');

				$options = [
					'type' => $type,
					'namespace' => $this->request->getData('namespace'),
				];

				$schema = null;
				try {
					$schema = (new Importer())->parse($json, $options);
				} catch (Exception $e) {
					$this->Flash->error($e->getMessage());
				}

				$this->set(compact('schema'));
			}
		}

	}

	/**
	 * @return void
	 */
	public function database(): void {
		$parser = new DatabaseParser();
		$tables = $parser->listTables();
		$this->set(compact('tables'));

		if ($this->request->is(['post', 'put'])) {
			if ($this->request->getData('dto')) {
				$format = $this->request->getData('format') ?: 'php';
				$options = [
					'namespace' => $this->request->getData('namespace'),
					'format' => $format,
				];
				$dto = $this->request->getData('dto');
				$namespace = $this->request->getData('namespace');
				$result = (new Importer())->buildSchema($dto, $options);
				$this->set(compact('result', 'dto', 'namespace', 'format'));
			} elseif ($this->request->getData('tables')) {
				$selectedTables = array_keys(array_filter($this->request->getData('tables')));

				$schema = null;
				try {
					$schema = $parser->parse($selectedTables);
				} catch (Exception $e) {
					$this->Flash->error($e->getMessage());
				}

				$this->set(compact('schema'));
			}
		}
	}

}
