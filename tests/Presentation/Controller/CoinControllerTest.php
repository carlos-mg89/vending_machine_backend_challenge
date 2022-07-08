<?php

namespace App\Tests\Presentation\Controller;

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

    public function testCoinControllerReturnCoinIsAlwaysSuccessful(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', "coin/RETURN-COIN");

        $this->assertResponseIsSuccessful();
    }
}
