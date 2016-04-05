<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Dictionary;
use Doctrine\ORM\EntityRepository;

class DictionaryRepository extends EntityRepository
{
    public function getGroupsWords($user)
    {
        $qb = $this->createQueryBuilder('d')
            ->where('d.groupWord = 1')
            ->andWhere('d.disabled = 0');

        if ($user) {
            $qb->andWhere('d.private = 0 OR d.user = :user')
                ->setParameter('user', $user);
        } else {
            $qb->andWhere('d.private = 0');
        }

        return $qb->getQuery()->getResult();
    }
}
