<?php

namespace App\Tests\Presentation\Controller;

use App\Domain\Model\AvailableCoin\Exception\CoinValueNotFound;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CoinControllerTest extends WebTestCase
{
    public function testCoinControllerInsertCoin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', "coin/1");

        $this->assertResponseIsSuccessful();
    }

    public function testCoinControllerInsertCoinWithInvalidCoinThrowsServerErrorStatus(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', "coin/2");

        $this->assertResponseStatusCodeSame(500);
    }
}
