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
}
