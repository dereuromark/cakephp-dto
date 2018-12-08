<?php

namespace TestApp\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $title
 * @property \TestApp\Model\Entity\Author $author
 * @property int $author_id
 * @property \TestApp\Model\Entity\Tag[] $tags
 * @property \DateTime $created
 */
class Article extends Entity {
}
