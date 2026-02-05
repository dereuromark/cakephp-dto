<?php
declare(strict_types=1);

namespace TestApp\Controller;

use CakeDto\Attribute\MapRequestDto;
use TestApp\Dto\PageDto;

class DtoResolverComponentController extends AppController {

	#[MapRequestDto(PageDto::class, source: MapRequestDto::SOURCE_QUERY, name: 'page')]
	public function create(): void {
	}

}
