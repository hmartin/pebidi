<?php

namespace Main\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * WordRepository
 */
class WordRepository extends EntityRepository
{
    public function getWordFullTranslation($w) 
    {
        $qb = $this->initQueryBuilder()
            ->addSelect('senses.sense as sense, senses.id as sid, GROUP_CONCAT(translation.word) as concat')
            ->innerJoin('ww.senses', 'senses')
            ->where('word.id = :wid')
            ->setParameter('wid', $w)
            ->groupBy('senses.id')
            ->orderBy('ww.priority', 'ASC');

        return $qb->getQuery()->getResult();      
    }
  
    public function getWordsForTest($nb, $d, $u)
    {
        $qb = $this->getDictionaryWords($d, $u)
            ->addSelect('word as object')
            ->setMaxResults($nb)
            ->orderBy('stat_sum_realised', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getWordsForSameTest($t)
    {
        $a = array(
            'tid' => $t
        );
        $qb = $this->initQueryBuilder()
            ->innerJoin('word.testsWords', 'test')
            ->where('test.id = :tid')
            ->groupBy('word.id')
            ->setParameters($a);

        return $qb->getQuery()->getResult();
    }

    public function getDictionaryAllWords($d, $u = null)
    {
        $qb = $this->getDictionaryWords($d, $u)
            ->orderBy('word.word', 'ASC');

        return $qb->getQuery()->getResult();
    }


    public function getDictionaryWords($d, $u = null)
    {

        $qb = $this->initQueryBuilder()
            ->addSelect('SUM(p.point)/COUNT(p.id) AS stat_sum_realised')
            ->innerJoin('word.dictionaries', 'd')
            ->leftJoin('word.points', 'p')
            ->where('d.id = :did')
            ->setParameter('did', $d)
            ->groupBy('word.id');

        if (is_object($u)) {
            $qb
                ->andWhere('d.user = :uid')
                ->setParameter('uid', $u);
        }

        return $qb;
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
