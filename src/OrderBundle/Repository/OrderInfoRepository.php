<?php

namespace OrderBundle\Repository;

/**
 * OrderInfoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderInfoRepository extends \Doctrine\ORM\EntityRepository
{
    public function findPhones() {
        $qb = $this->createQueryBuilder('order_info')
            ->select('DISTINCT order_info.customerPhone')
            ->where('order_info.enabled = true');
        return $qb->getQuery()->getResult();
    }
}
