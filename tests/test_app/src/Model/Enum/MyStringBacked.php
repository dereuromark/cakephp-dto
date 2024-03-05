<?php
declare(strict_types=1);

namespace TestApp\Model\Enum;

use Cake\Database\Type\EnumLabelInterface;
use Cake\Utility\Inflector;

/**
 * StringB Enum
 */
enum MyStringBacked: string implements EnumLabelInterface
{
	case Foo = 'f';
	case Bar = 'b';

	/**
	 * @return string
	 */
	public function label(): string {
		return Inflector::humanize(Inflector::underscore($this->name));
	}
}
