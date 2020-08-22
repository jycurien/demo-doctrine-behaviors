<?php

namespace App\Entity;

use App\Form\DTO\ArticleDTO;
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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function createDTO(ParameterBagInterface $parameterBag)
    {
        return new ArticleDTO($parameterBag, $this);
    }

    public function updateFromDTO(ArticleDTO $articleDTO)
    {
        if (!$articleDTO->getTranslations()->isEmpty()) {
            foreach ($articleDTO->getTranslations() as $locale => $translation) {
                if (!empty($translation->getTitle()) && !empty($translation->getBody())) {
                    $this->translate($locale, false)->setTitle($translation->getTitle());
                    $this->translate($locale, false)->setBody($translation->getBody());
                }
            }
            $this->mergeNewTranslations();
        }
    }
}
