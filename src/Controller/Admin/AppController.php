<?php
declare(strict_types=1);

namespace CakeDto\Controller\Admin;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\Log\Log;
use Closure;
use Throwable;

/**
 * Base controller for CakeDto Admin controllers.
 *
 * The admin UI introspects the application's database schema and writes
 * generated DTO files, so accidental exposure to anonymous users is a
 * serious problem. The default policy is therefore **deny**. The host
 * application MUST set `CakeDto.adminAccess` to a `Closure` that receives
 * the current request and returns literal `true` to grant access; anything
 * else (unset, non-Closure, returns false, returns a truthy non-bool, or
 * throws) yields a 403.
 *
 * ```php
 * Configure::write('CakeDto.adminAccess', function (\Cake\Http\ServerRequest $request): bool {
 *     $identity = $request->getAttribute('identity');
 *     return $identity !== null && in_array('admin', (array)$identity->roles, true);
 * });
 * ```
 *
 * Subclasses do not need to opt out of authentication / authorization
 * components: this gate is the authorization decision for the admin UI,
 * and the `Authorization` component policy check is silenced here so
 * controllers do not have to call `skipAuthorization()` themselves.
 */
class AppController extends Controller {

	/**
	 * Default-deny access gate.
	 *
	 * @param \Cake\Event\EventInterface<\Cake\Controller\Controller> $event
	 * @throws \Cake\Http\Exception\ForbiddenException When access is denied or unconfigured.
	 * @return void
	 */
	public function beforeFilter(EventInterface $event): void {
		parent::beforeFilter($event);

		// Coexist with cakephp/authorization: this gate IS the authorization
		// decision for the CakeDto admin, so silence the policy check.
		if ($this->components()->has('Authorization') && method_exists($this->components()->get('Authorization'), 'skipAuthorization')) {
			$this->components()->get('Authorization')->skipAuthorization();
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
		} catch (ForbiddenException $e) {
			// Caller explicitly chose the 403 path - respect it.
			throw $e;
		} catch (Throwable $e) {
			// Convert any other failure (broken callable, transient DB
			// error in a role lookup, etc.) to a generic 403. Logging the
			// concrete exception class + message lets operators diagnose
			// it without leaking a stack trace to the client.
			Log::warning(sprintf(
				'CakeDto admin gate threw %s: %s',
				$e::class,
				$e->getMessage(),
			));

			throw new ForbiddenException('Not authorized to access CakeDto admin UI');
		}

		if (!$allowed) {
			throw new ForbiddenException('Not authorized to access CakeDto admin UI');
		}
	}

}
