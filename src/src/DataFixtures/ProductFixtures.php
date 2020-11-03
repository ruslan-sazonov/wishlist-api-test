<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i <= 50; $i++) {
            $product = new Product();
            $product->setName(rtrim($faker->sentence(3, false), '.'));
            $product->setSku(strtoupper($faker->unique()->uuid));
            $product->setPrice($faker->numberBetween(100, 100000));
            $product->setIsActive($faker->boolean);
            $product->setCreatedAt($faker->dateTimeBetween('-1 year', 'now'));
            $product->setCreatedBy(0);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
