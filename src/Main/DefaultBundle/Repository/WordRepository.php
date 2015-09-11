<?php

namespace Main\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * WordRepository
 */
class WordRepository extends EntityRepository
{
    public function getWordTranslationConcat($w) 
    {
        $qb = $this->getWordFullTranslationQuery($w) 
            ->addSelect('senses.sense as sense, senses.id as sid, GROUP_CONCAT(trans_word.word SEPARATOR \', \') as concat')
            ->groupBy('senses.id');

        return $qb->getQuery()->getResult();      
    }
    
    protected function getWordFullTranslationQuery($w) 
    {
        $qb = $this->initQueryBuilder()
            ->innerJoin('ww.senses', 'senses')
            ->where('word.id = :wid')
            ->setParameter('wid', $w)
            ->orderBy('ww.priority', 'ASC');

        return $qb;      
    }
    
    public function getWordFullTranslation($w) 
    {
        return $this->getWordFullTranslationQuery($w)->getQuery()->getResult();      
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
            ->select('word.id, word.word as w, trans_word.word as t')

            ->innerJoin('word.wordTypes', 'wt')
            ->innerJoin('\Main\DefaultBundle\Entity\Ww', 'ww',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'wt.id =  ww.word1')
            ->innerJoin('\Main\DefaultBundle\Entity\WordType', 'translation',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'ww.word2 =  translation.id')

            ->innerJoin('translation.word', 'trans_word')
            ;
    }
}
