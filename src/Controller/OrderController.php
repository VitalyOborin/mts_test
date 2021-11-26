<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Exception\ActionException;
use App\Service\Order\Action\CreateOrderAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /*
    #[Route('/order/list', name: 'orders_list', methods: ['GET'])]
    public function ordersList(): Response
    {
        $orders = [];

        return $this->json([
            'orders' => $orders,
        ]);
    }
*/
    #[Route('/order/create', name: 'orders_create', methods: ['POST'])]
    public function orderCreate(
        CreateOrderAction $action
    ): Response {
        return $this->json(
            $action->exec()->getResult()
        );
    }
}
