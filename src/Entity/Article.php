<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\SoftDeletableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\UuidableInterface;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Knp\DoctrineBehaviors\Model\Uuidable\UuidableTrait;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article implements UuidableInterface, TimestampableInterface,
    SoftDeletableInterface, TranslatableInterface
{
    use UuidableTrait;
    use TimestampableTrait;
    use SoftDeletableTrait;
    use TranslatableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

//    public function getTitle()
//    {
//        return $this->translate(null, true)->getTitle();
//    }
//
//    public function getBody()
//    {
//        return $this->translate(null, true)->getBody();
//    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
