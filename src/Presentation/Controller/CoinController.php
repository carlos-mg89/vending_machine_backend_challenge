<?php

namespace App\Presentation\Controller;

use App\Application\Service\InsertCoinService;
use App\Application\Service\ReturnCoinService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/coin')]
class CoinController extends AbstractController
{
    #[Route('/RETURN-COIN', name: 'return_coin')]
    function returnCoinAction(ReturnCoinService $returnCoinService): Response
    {
        $previouslyInsertedCoins = $returnCoinService->execute();

        return new JsonResponse($previouslyInsertedCoins);
    }

    #[Route(
        '/{coinValue}',
        name: 'insert_coin',
        requirements: ["coinValue" => "(?:\d+|\d*\.\d+)"]
    )]
    function insertCoinAction(
        float $coinValue,
        InsertCoinService $insertCoinService,
    ): Response {
        $responseStatus = 200;

        try {
            $insertCoinService->execute($coinValue);
        } catch (Exception) {
            $responseStatus = 500;
        }

        return new JsonResponse(null, $responseStatus);
    }
}
