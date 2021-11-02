<?php

namespace TestApp\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $title
 * @property \TestApp\Model\Entity\Author $author
 * @property int $author_id
 * @property array<\TestApp\Model\Entity\Tag> $tags
 * @property \DateTimeInterface $created
 */
class Article extends Entity {
}
