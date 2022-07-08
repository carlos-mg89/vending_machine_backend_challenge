<?php

namespace App\Presentation\Controller;

use App\Application\Service\InsertCoinService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/coin')]
class CoinController extends AbstractController
{
    #[Route(
        '/{coinValue}',
        name: 'insert_coin',
        requirements: ["coinValue" => "(?:\d+|\d*\.\d+)"]
    )]
    function insertCoinAction(
        float $coinValue,
        InsertCoinService $insertCoinService,
    ): Response {
        $insertCoinService->execute($coinValue);

        return new JsonResponse();
    }
}
