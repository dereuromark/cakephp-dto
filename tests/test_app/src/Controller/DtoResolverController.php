<?php
declare(strict_types=1);

namespace TestApp\Controller;

use Cake\Controller\Controller;
use Cake\Http\Response;
use CakeDto\Attribute\MapRequestDto;
use TestApp\Dto\PageDto;

class DtoResolverController extends Controller {

	public function create(#[MapRequestDto(source: MapRequestDto::SOURCE_QUERY)] PageDto $page): Response {
		return $this->response->withStringBody((string)$page->getNumber());
	}

}
