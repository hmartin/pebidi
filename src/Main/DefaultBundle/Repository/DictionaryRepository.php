<?php

namespace Main\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DictionaryRepository extends EntityRepository
{
    public function getGroupsWords($user)
    {
        $qb = $this->createQueryBuilder('dic')
            ->where('dic.groupWord = 1')
            ->andWhere('dic.private = 0 OR dic.user = :user')
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }

}
