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
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence())->setBody($faker->paragraph());
            $manager->persist($article);
        }

        $manager->flush();
    }
}
