<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method CartItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartItem[]    findAll()
 * @method CartItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartItem::class);
    }

    public function findOrCreateByProduct(Product $product, int $owner): CartItem
    {
        $cartItem = $this->findOneBy(['product' => $product]);
        if (!$cartItem) {
            $cartItem = new CartItem();
            $cartItem
                ->setProduct($product)
                ->setQty(1)
                ->setPrice($product->getPrice())
                ->setOwner($owner);
            $this->_em->persist($cartItem);
            $this->_em->flush();
        }
        return $cartItem;
    }

    public function addProductToCart(Product $product, int $qty = 1, int $owner = 0): bool
    {
        // найдем товар в корзине, но не в заказе для текущего пользователя
        $cartItem = $this->findOneBy(['product' => $product, 'owner' => $owner, 'order' => null]);
        if ($cartItem) { // товар существует
            if ($cartItem->getQty() + $qty <= $product->getStock()) {
                $cartItem->setQty($cartItem->getQty() + $qty);
            } else {
                throw new Exception('Not allowed to add more', 3);
            }
        } elseif ($qty <= $product->getStock()) { // товара нет в корзине, добавляем нужное количество к нему
            $cartItem = new CartItem();
            $cartItem
                ->setQty($qty)
                ->setPrice($product->getPrice())
                ->setProduct($product)
                ->setOwner($owner);
        } else {
            throw new Exception('Wrong quantity requested', 4);
        }
        try {
            $this->_em->persist($cartItem);
            $this->_em->flush();
        } catch (Exception $exception) {
            // ?
        }
        return $cartItem->getId() > 0;
    }
}
