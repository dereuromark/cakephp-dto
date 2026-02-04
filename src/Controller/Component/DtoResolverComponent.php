<?php

declare(strict_types=1);

namespace CakeDto\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Http\ServerRequest;
use Cake\Utility\Inflector;
use CakeDto\Attribute\MapRequestDto;
use InvalidArgumentException;
use PhpCollective\Dto\Dto\Dto;
use ReflectionAttribute;
use ReflectionMethod;

class DtoResolverComponent extends Component {

	/**
	 * @param \Cake\Event\EventInterface $event
	 *
	 * @return void
	 */
	public function beforeFilter(EventInterface $event): void {
		$controller = $this->getController();
		$action = $controller->getRequest()->getParam('action');
		if (!$action) {
			return;
		}

		$this->resolveActionDtos($controller, $action);
	}

	/**
	 * @param \Cake\Controller\Controller $controller
	 * @param string $action
	 *
	 * @return void
	 */
	public function resolveActionDtos(Controller $controller, string $action): void {
		if (!method_exists($controller, $action)) {
			return;
		}

		$method = new ReflectionMethod($controller, $action);
		$attributes = $method->getAttributes(MapRequestDto::class, ReflectionAttribute::IS_INSTANCEOF);
		if (!$attributes) {
			return;
		}

		$request = $controller->getRequest();
		foreach ($attributes as $attribute) {
			/** @var \CakeDto\Attribute\MapRequestDto $config */
			$config = $attribute->newInstance();
			$request = $this->mapRequestToDto($request, $config);
		}

		$controller->setRequest($request);
	}

	/**
	 * @param \Cake\Http\ServerRequest $request
	 * @param \CakeDto\Attribute\MapRequestDto $config
	 *
	 * @return \Cake\Http\ServerRequest
	 */
	protected function mapRequestToDto(ServerRequest $request, MapRequestDto $config): ServerRequest {
		$dtoClass = $config->class;
		if (!class_exists($dtoClass) || !is_subclass_of($dtoClass, Dto::class)) {
			throw new InvalidArgumentException('DTO class must extend ' . Dto::class . ': ' . $dtoClass);
		}

		$data = $this->extractData($request, $config->source);
		$dto = $dtoClass::createFromArray($data);

		$attributeName = $config->name ?? $this->defaultAttributeName($dtoClass);

		return $request->withAttribute($attributeName, $dto);
	}

	/**
	 * @param \Cake\Http\ServerRequest $request
	 * @param string $source
	 *
	 * @return array<string, mixed>
	 */
	protected function extractData(ServerRequest $request, string $source): array {
		return match ($source) {
			MapRequestDto::SOURCE_BODY => (array)$request->getData(),
			MapRequestDto::SOURCE_QUERY => $request->getQueryParams(),
			MapRequestDto::SOURCE_REQUEST => array_merge($request->getQueryParams(), (array)$request->getData()),
			default => $this->extractAutoData($request),
		};
	}

	/**
	 * @param \Cake\Http\ServerRequest $request
	 *
	 * @return array<string, mixed>
	 */
	protected function extractAutoData(ServerRequest $request): array {
		if (in_array($request->getMethod(), ['GET', 'HEAD'], true)) {
			return $request->getQueryParams();
		}

		$data = (array)$request->getData();
		if ($data !== []) {
			return $data;
		}

		return $request->getQueryParams();
	}

	/**
	 * @param string $dtoClass
	 *
	 * @return string
	 */
	protected function defaultAttributeName(string $dtoClass): string {
		$baseName = strrpos($dtoClass, '\\') !== false ? (string)substr($dtoClass, strrpos($dtoClass, '\\') + 1) : $dtoClass;
		$baseName = preg_replace('/Dto$/', '', $baseName) ?: $baseName;
		$variable = Inflector::variable($baseName);

		return $variable !== '' ? $variable : 'dto';
	}

}
