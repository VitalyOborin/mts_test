<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product
                ->setPrice(rand(100, 1000)*100-100)
                ->setTitle(sprintf('Название товара %s', $i))
                ->setStock(rand(0, 10))
            ;
            $manager->persist($product);
        }
        $manager->flush();
    }
}
