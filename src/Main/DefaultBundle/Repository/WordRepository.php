<?php

namespace Main\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * WordRepository
 */
class WordRepository extends EntityRepository
{
    public function getWordsForTest($nb, $d, $u) {
        $a =  array(
            'did' => $d,
            'uid' => $u,
        );
        $qb = $this->initQueryBuilder()
            ->addSelect('SUM(p.point)/COUNT(p.id) AS stat_sum_realised')
            ->innerJoin('word.dictionaries', 'd')
            ->leftJoin('word.points','p')
            ->where('d.id = :did')
            ->andWhere('d.user = :uid')
            ->groupBy('word.id')
            ->setParameters($a)
            ->setMaxResults($nb)
            ->orderBy('stat_sum_realised', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }
    public function getWordsForSameTest($t) {
        $a =  array(
            'tid' => $t
        );
        $qb = $this->initQueryBuilder()
            ->innerJoin('word.testsWords','test')
            ->where('test.id = :tid')
            ->groupBy('word.id')
            ->setParameters($a)
        ;

        return $qb->getQuery()->getResult();
    }

    private function initQueryBuilder()
    {
        return $this->createQueryBuilder('word')
            ->select('word.id, word.word as w, translation.word as t')
            ->innerJoin('\Main\DefaultBundle\Entity\Ww', 'ww',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'word.id =  ww.word1')
            ->innerJoin('\Main\DefaultBundle\Entity\Word', 'translation',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'ww.word2 =  translation.id');
    }
}
