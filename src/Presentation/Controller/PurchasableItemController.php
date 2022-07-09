<?php

namespace App\Presentation\Controller;

use App\Application\Service\PurchaseItemService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/purchasable_item')]
class PurchasableItemController extends AbstractController
{
    #[Route('/GET-{selector}', name: 'purchase_item')]
    function purchaseItemAction(
        string $selector,
        PurchaseItemService $purchaseItemService,
    ): Response {
        try {
            $purchasedItemSelector = $purchaseItemService->execute($selector);

            return new JsonResponse($purchasedItemSelector);
        } catch (Exception $exception) {
            return new JsonResponse([$exception->getMessage()]);
        }
    }
}
