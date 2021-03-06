<?php

namespace OrderBundle\Repository;

/**
 * OrderItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderItemRepository extends \Doctrine\ORM\EntityRepository
{
    public function countRevenue()
    {
        $qb = $this->createQueryBuilder('order_item')
            ->leftJoin('order_item.commande', 'c')
            ->leftJoin('order_item.product', 'p')
            ->addSelect('SUM(p.price) as total_revenue')
            ->groupBy('order_item.commande')
            ->where('c.archived = true')
            ->andWhere('c.enabled = true');

        return $qb->getQuery()->getResult();
    }
}
