<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CartItem;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function create(Order $order): int
    {
        // заказ существующей корзины
        $cartItemRepository = $this->_em->getRepository(CartItem::class);
        $cartItems = $cartItemRepository->findBy(['owner' => $order->getOwner(), 'order' => null]);
        $this->_em->beginTransaction();
        try {
            $this->_em->persist($order);
            $this->_em->flush();
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->getProduct();
                if ($cartItem->getQty() <= $product->getStock()) {
                    $this->_em->persist($cartItem);
                    $cartItem->getProduct()->setStock($product->getStock()-$cartItem->getQty());
                    $this->_em->persist($product);
                } else {
                    throw new Exception(sprintf('Product id=%d out of stock', $cartItem->getProduct()->getId()), 10);
                }
                $cartItem->setOrder($order);
            }
            $this->_em->flush();
            $this->_em->commit();
            return $order->getId();
        } catch (Exception $exception) {
            $this->_em->rollback();
            throw new Exception('Order was not created', 11, $exception);
        }
    }
}
