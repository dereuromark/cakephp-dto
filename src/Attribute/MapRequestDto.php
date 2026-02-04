<?php

declare(strict_types=1);

namespace CakeDto\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class MapRequestDto {

	/**
	 * @var string
	 */
	public const SOURCE_BODY = 'body';

	/**
	 * @var string
	 */
	public const SOURCE_QUERY = 'query';

	/**
	 * @var string
	 */
	public const SOURCE_REQUEST = 'request';

	/**
	 * @var string
	 */
	public const SOURCE_AUTO = 'auto';

	/**
	 * @param string $class DTO class name
	 * @param string $source Data source: body, query, request, or auto
	 * @param string|null $name Request attribute name (defaults to dto or inferred from class)
	 */
	public function __construct(
		public readonly string $class,
		public readonly string $source = self::SOURCE_AUTO,
		public readonly ?string $name = null,
	) {
	}

}
