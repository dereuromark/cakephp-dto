<?php
declare(strict_types=1);

namespace CakeDto\Controller\Admin;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\HttpException;
use Cake\Log\Log;
use Closure;
use Throwable;

/**
 * Access gate for the schema preview and database importer.
 *
 * Configure CakeDto.adminAccess with a Closure returning literal true for
 * permitted requests. The controller extends CakePHP's base Controller;
 * application middleware still applies.
 */
class AppController extends Controller {

	public function initialize(): void {
		parent::initialize();
		$this->loadComponent('Flash');
	}

	/**
	 * Default-deny access gate.
	 *
	 * @param \Cake\Event\EventInterface<\Cake\Controller\Controller> $event
	 * @throws \Cake\Http\Exception\ForbiddenException When access is denied or unconfigured.
	 * @return void
	 */
	public function beforeFilter(EventInterface $event): void {
		parent::beforeFilter($event);

		$authorization = $this->request->getAttribute('authorization');
		if (is_object($authorization)) {
			if ($this->components()->has('Authorization')) {
				$component = $this->components()->get('Authorization');
				if (method_exists($component, 'skipAuthorization')) {
					$component->skipAuthorization();
				}
			} elseif (method_exists($authorization, 'skipAuthorization')) {
				$authorization->skipAuthorization();
			}
		}

		$adminAccess = Configure::read('CakeDto.adminAccess');
		if ($adminAccess instanceof Closure) {
			$request = $this->request;
			$this->runGate(static fn (): mixed => $adminAccess($request));

			return;
		}
		if ($adminAccess !== null) {
			throw new ForbiddenException('CakeDto.adminAccess must be a Closure');
		}

		throw new ForbiddenException(
			'CakeDto admin UI is not configured. Set CakeDto.adminAccess to a Closure that returns true for permitted callers.',
		);
	}

	/**
	 * Run the gate Closure, normalising every non-true outcome to a 403 and
	 * logging unexpected exceptions instead of leaking them to the client.
	 *
	 * @param \Closure $gate
	 * @throws \Cake\Http\Exception\ForbiddenException
	 * @return void
	 */
	private function runGate(Closure $gate): void {
		try {
			$allowed = $gate() === true;
		} catch (HttpException $e) {
			throw $e;
		} catch (Throwable $e) {
			Log::warning(sprintf(
				'CakeDto admin gate threw %s in %s:%d',
				$e::class,
				$e->getFile(),
				$e->getLine(),
			));

			throw new ForbiddenException('Not authorized to access CakeDto admin UI');
		}

		if (!$allowed) {
			throw new ForbiddenException('Not authorized to access CakeDto admin UI');
		}
	}

}
