<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\User;

class ResultRepository extends EntityRepository
{
    public function getAvgScore(User $u)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('AVG(r.score) as score')
            ->where('r.user = :uid')
            ->setParameter('uid', $u);
        $r = $qb->getQuery()->getOneOrNullResult();

        if (!isset($r['score'])) {
            return 0;
        }

        return $r['score'];
    }
}
