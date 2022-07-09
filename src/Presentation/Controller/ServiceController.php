<?php

namespace App\Presentation\Controller;

use App\Application\Service\SetMachineStateService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/SERVICE')]
class ServiceController extends AbstractController
{
    #[Route(
        '',
        name: 'service_vending_machine',
    )]
    function serviceAction(
        SetMachineStateService $machineStateService,
        Request $request,
    ): Response {
        $responseStatus = 200;

        try {
            $itemsStockJson = $request->get("items_stock", "[]");
            $availableCoinsJson = $request->get("available_coins", "[]");

            $machineStateService->execute($itemsStockJson, $availableCoinsJson);
        } catch (Exception) {
            $responseStatus = 500;
        }

        return new Response(null, $responseStatus);
    }
}
