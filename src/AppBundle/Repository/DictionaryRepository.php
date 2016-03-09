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
            ->andWhere('d.private = 0 OR d.user = :user')
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }

    public function createJson(Dictionary $d)
    {
        $a = $d->getJsonArray();

        $a['countWord'] = $this->createQueryBuilder('d')->select('COUNT(words.id)')->leftJoin('d.words', 'words')
            ->where('words.disabled = 0')->groupBy('d.id')->getQuery()->getSingleScalarResult();

        return $a;
    }

}
