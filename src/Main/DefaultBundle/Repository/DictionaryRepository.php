<?php

namespace Main\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DictionaryRepository extends EntityRepository
{
    public function getGroupsWords($user)
    {
        $qb = $this->createQueryBuilder('d')
            ->where('d.groupWord = 1')
            ->andWhere('d.private = 0 OR d.user = :user')
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }

}
