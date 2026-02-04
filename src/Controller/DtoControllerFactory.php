<?php

declare(strict_types=1);

namespace CakeDto\Controller;

use Cake\Controller\ControllerFactory;
use Cake\Controller\Exception\InvalidParameterException;
use Cake\Http\ServerRequest;
use CakeDto\Attribute\MapRequestDto;
use Closure;
use PhpCollective\Dto\Dto\Dto;
use ReflectionAttribute;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;

class DtoControllerFactory extends ControllerFactory {

	/**
	 * @param \Closure $action Controller action.
	 * @param array $passedParams Params passed by the router.
	 *
	 * @return array
	 */
	protected function getActionArgs(Closure $action, array $passedParams): array {
		$resolved = [];
		$function = new ReflectionFunction($action);
		$request = $this->controller->getRequest();
		if (!$request instanceof ServerRequest) {
			throw new InvalidParameterException(['template' => 'missing_parameter', 'parameter' => 'request']);
		}

		foreach ($function->getParameters() as $parameter) {
			$attribute = $this->getDtoAttribute($parameter);
			if ($attribute !== null) {
				$resolved[] = $this->resolveDtoFromRequest($parameter, $attribute, $request);

				continue;
			}

			$type = $parameter->getType();
			if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
				$typeName = $type->getName();
				if ($this->container->has($typeName)) {
					$resolved[] = $this->container->get($typeName);

					continue;
				}
				if ($passedParams && $passedParams[0] instanceof $typeName) {
					$resolved[] = array_shift($passedParams);

					continue;
				}
				if ($parameter->isDefaultValueAvailable()) {
					$resolved[] = $parameter->getDefaultValue();

					continue;
				}

				throw new InvalidParameterException([
					'template' => 'missing_dependency',
					'parameter' => $parameter->getName(),
					'type' => $typeName,
					'controller' => $this->controller->getName(),
					'action' => $this->controller->getRequest()->getParam('action'),
					'prefix' => $this->controller->getRequest()->getParam('prefix'),
					'plugin' => $this->controller->getRequest()->getParam('plugin'),
				]);
			}

			if ($passedParams) {
				$argument = array_shift($passedParams);
				if (is_string($argument) && $type instanceof ReflectionNamedType) {
					$typedArgument = $this->coerceStringToType($argument, $type);
					if ($typedArgument === null) {
						throw new InvalidParameterException([
							'template' => 'failed_coercion',
							'passed' => $argument,
							'type' => $type->getName(),
							'parameter' => $parameter->getName(),
							'controller' => $this->controller->getName(),
							'action' => $this->controller->getRequest()->getParam('action'),
							'prefix' => $this->controller->getRequest()->getParam('prefix'),
							'plugin' => $this->controller->getRequest()->getParam('plugin'),
						]);
					}
					$argument = $typedArgument;
				}

				$resolved[] = $argument;

				continue;
			}

			if ($parameter->isDefaultValueAvailable()) {
				$resolved[] = $parameter->getDefaultValue();

				continue;
			}

			if ($parameter->isVariadic()) {
				continue;
			}

			throw new InvalidParameterException([
				'template' => 'missing_parameter',
				'parameter' => $parameter->getName(),
				'controller' => $this->controller->getName(),
				'action' => $this->controller->getRequest()->getParam('action'),
				'prefix' => $this->controller->getRequest()->getParam('prefix'),
				'plugin' => $this->controller->getRequest()->getParam('plugin'),
			]);
		}

		return array_merge($resolved, $passedParams);
	}

	/**
	 * @param \ReflectionParameter $parameter
	 *
	 * @return \CakeDto\Attribute\MapRequestDto|null
	 */
	protected function getDtoAttribute(ReflectionParameter $parameter): ?MapRequestDto {
		/** @var array<\CakeDto\Attribute\MapRequestDto> $attributes */
		$attributes = $parameter->getAttributes(MapRequestDto::class, ReflectionAttribute::IS_INSTANCEOF);
		foreach ($attributes as $attribute) {
			return $attribute->newInstance();
		}

		return null;
	}

	/**
	 * @param \ReflectionParameter $parameter
	 * @param \CakeDto\Attribute\MapRequestDto $attribute
	 * @param \Cake\Http\ServerRequest $request
	 *
	 * @return \PhpCollective\Dto\Dto\Dto
	 */
	protected function resolveDtoFromRequest(ReflectionParameter $parameter, MapRequestDto $attribute, ServerRequest $request): Dto {
		$dtoClass = $attribute->class;
		if ($dtoClass === null) {
			$type = $parameter->getType();
			if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
				$dtoClass = $type->getName();
			}
		}

		if ($dtoClass === null) {
			throw new InvalidParameterException([
				'template' => 'missing_dependency',
				'parameter' => $parameter->getName(),
				'type' => 'Dto',
			]);
		}

		if (!class_exists($dtoClass) || !is_subclass_of($dtoClass, Dto::class)) {
			throw new InvalidParameterException([
				'template' => 'missing_dependency',
				'parameter' => $parameter->getName(),
				'type' => $dtoClass,
			]);
		}

		$data = $this->extractData($request, $attribute->source);

		/** @var class-string<\PhpCollective\Dto\Dto\Dto> $dtoClass */
		return $dtoClass::createFromArray($data);
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
	 * @param \ReflectionParameter $parameter
	 * @param array $passedParams
	 *
	 * @return mixed
	 */

}
