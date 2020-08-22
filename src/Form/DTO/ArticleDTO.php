<?php


namespace App\Form\DTO;

use App\Entity\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ArticleDTO
{
    private $locales;
    private $translations;

    public function __construct(ParameterBagInterface $parameterBag, Article $article)
    {
        $this->defaultLocale = $parameterBag->get('app.default_locale');
        $this->locales = $parameterBag->get('app.supported_locales_array');
        $this->translations = new ArrayCollection();

        $translations = $article->getTranslations();
        if (!$translations->isEmpty()) {
            foreach ($translations as $translation) {
                if (in_array($translation->getLocale(), $this->locales)) {
                    $this->addTranslation($translation->getLocale(), new ArticleTranslationDTO(
                        $translation->getTitle(),
                        $translation->getBody()
                    ));
                }
            }
        }

        foreach ($this->locales as $locale) {
            if (!$this->translations->containsKey($locale)) {
                $this->addTranslation($locale, new ArticleTranslationDTO(
                    null,
                    null
                ));
            }
        }
    }

    /**
     * @return mixed
     */
    public function getLocales()
    {
        return $this->locales;
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations(): ArrayCollection
    {
        return $this->translations;
    }

    public function addTranslation(string $locale, ArticleTranslationDTO $articleTranslationDTO)
    {
        if (!$this->translations->containsKey($locale)) {
            $this->translations[$locale] = $articleTranslationDTO;
        }
    }
}