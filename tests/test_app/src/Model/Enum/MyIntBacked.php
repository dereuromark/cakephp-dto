<?php
declare(strict_types=1);

namespace TestApp\Model\Enum;

use Cake\Database\Type\EnumLabelInterface;
use Cake\Utility\Inflector;

enum MyIntBacked: int implements EnumLabelInterface
{
	case Foo = 1;
	case Bar = 0;

	/**
	 * @return string
	 */
	public function label(): string {
		return Inflector::humanize(Inflector::underscore($this->name));
	}
}
