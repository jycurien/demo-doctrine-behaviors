<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $englishFaker = Factory::create('en_EN');
        $spanishFaker = Factory::create('es_ES');
        $frenchFaker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $article = new Article();
            $article->translate('en')->setTitle($englishFaker->realText(50))->setBody($englishFaker->realText(200));
            $article->translate('es')->setTitle($spanishFaker->realText(50))->setBody($spanishFaker->realText(200));
            $article->translate('fr')->setTitle($frenchFaker->realText(50))->setBody($frenchFaker->realText(200));
            $manager->persist($article);
            $article->mergeNewTranslations();
        }

        $manager->flush();
    }
}
