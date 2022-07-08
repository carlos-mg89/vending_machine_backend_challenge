<?php

namespace App\DataFixtures;

use App\Domain\Model\AvailableCoin\Entity\AvailableCoin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AvailableCoinFixture extends Fixture
{
    public const AVAILABLE_COIN_VALUES = [
        0.05,
        0.1,
        0.25,
        1.0,
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::AVAILABLE_COIN_VALUES as $availableCoinValue) {
            $availableCoin = new AvailableCoin();
            $availableCoin
                ->setCoinValue($availableCoinValue)
                ->setCoinStock(1);
            $manager->persist($availableCoin);
        }

        $manager->flush();
    }
}
