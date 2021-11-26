<?php

declare(strict_types=1);

namespace App\Service\Order\Action;

use App\Entity\Order;
use App\Repository\CartItemRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\ActionInterface;
use App\Service\Exception\ActionException;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

class CreateOrderAction implements ActionInterface
{
    private array $cart = [];
    private string $email = '';
    private int $orderId = 0;

    protected const ERROR_ORDER_NOT_CREATED = 1;
    protected const ERROR_EMPTY_CART = 2;

    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository,
        private CartItemRepository $cartItemRepository,
        private OrderRepository $orderRepository
    ) {
        $data = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);
        $this->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $this->cart = array_map("intval", $data['products']);
    }

    public function exec(): self
    {
        $owner = 100; // test
        try {
            if (count($this->cart)) {
                foreach ($this->cart as $productId => $qty) {
                    $product = $this->productRepository->find($productId);
                    // по-хорошему, добавление в корзину должно происходить через другой контроллер
                    $this->cartItemRepository->addProductToCart(
                        $product,
                        $qty,
                        $owner
                    );
                }
                $order = (new Order())->setEmail($this->email)->setOwner($owner);
                try {
                    $this->orderId = $this->orderRepository->create($order);
                } catch (Exception $exception) {
                    throw new Exception("Order was not created", self::ERROR_ORDER_NOT_CREATED, $exception);
                }
            } else {
                throw new Exception('Empty cart', self::ERROR_EMPTY_CART);
            }
        } catch (Exception $exception) {
            throw new ActionException('Action error', $exception);
        }
        return $this;
    }

    public function getResult(): array
    {
        if ($this->orderId) {
            return ['order_id' => $this->orderId];
        } else {
            return ['error' => 'order was not created']; // todo exception
        }
    }
}
