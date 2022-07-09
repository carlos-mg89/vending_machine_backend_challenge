<?php

namespace App\DataFixtures;

use App\Domain\Model\PurchasableItem\Entity\PurchasableItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PurchasableItemFixture extends Fixture
{
    public const PURCHASABLE_ITEMS = [
        "Water" => 0.65,
        "Juice" => 1.00,
        "Soda" => 1.50,
    ];
    public const INITIAL_STOCK = 3;

    public function load(ObjectManager $manager): void
    {
        foreach (self::PURCHASABLE_ITEMS as $selector => $price) {
            $purchasableItem = new PurchasableItem();
            $purchasableItem
                ->setSelector($selector)
                ->setPrice($price)
                ->setStock(self::INITIAL_STOCK)
            ;
            $manager->persist($purchasableItem);
        }

        $manager->flush();
    }
}
