<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * WordRepository
 */
class WordRepository extends EntityRepository
{
    //const selectGroupConcat = 'GROUP_CONCAT_IF_NULL(DISTINCT IFNULL(translation.expression, trans_word.word) SEPARATOR \', \') as concat';
    const selectGroupConcat = 'GROUP_CONCAT_IF_NULL(DISTINCT IFNULL(translation.expression, trans_word.word) ORDER BY ww.priority, ww.id SEPARATOR \', \') as concat';

    public function getWordTranslationConcat($w)
    {
        $qb = $this->getWordFullTranslationQuery($w)
            ->addSelect(' wt.category, ww.additional,  ' . self::selectGroupConcat)
            ->groupBy('wt.id');

        return $qb->getQuery()->getResult();
    }

    protected function getWordFullTranslationQuery($w)
    {
        $qb = $this->initQueryBuilder()
            ->addSelect('wt.sense as sense')
            ->where('word.id = :wid')
            ->setParameter('wid', $w)
            ->orderBy('word.word', 'ASC');

        return $qb;
    }

    private function initQueryBuilder()
    {
        return $this->createQueryBuilder('word')
            ->select('word.id, IFNULL(wt.expression, word.word) as w, trans_word.word as t, word.score as global')
            ->innerJoin('word.subWords', 'wt')
            ->innerJoin('\AppBundle\Entity\Ww', 'ww',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'wt.id =  ww.word1')
            ->innerJoin('\AppBundle\Entity\SubWord', 'translation',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'ww.word2 =  translation.id')
            ->innerJoin('translation.word', 'trans_word')
            ->andWhere('word.disabled = 0')
            ;
    }

    public function getWordFullTranslation($w)
    {
        return $this->getWordFullTranslationQuery($w)->addSelect('wt.expression')->getQuery()->getResult();
    }

    public function getWordsForTest($nb, $d, $u)
    {
        $qb = $this->getDictionaryWords($d, $u)
            ->addSelect('word as object')
            ->setMaxResults($nb)
            ->orderBy('stat_sum_realised', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getDictionaryWords($d, $u = null)
    {
        // TODO : optim query and start by select dictionary
        $qb = $this->initQueryBuilder()
            ->addSelect('dw.score AS stat_sum_realised, ' . self::selectGroupConcat)
            ->innerJoin('word.dictionaryWords', 'dw')
            ->innerJoin('dw.dictionary', 'd')
            ->leftJoin('word.points', 'p')
            ->where('d.id = :did')
            ->andWhere('wt.expression IS NULL')
            ->setParameter('did', $d)
            ->groupBy('word.id');

        if (is_object($u)) {
            $qb
                ->leftJoin('p.result', 'r',
                    \Doctrine\ORM\Query\Expr\Join::WITH, 'r.user = :uid')
                ->setParameter('uid', 3);
        }

        return $qb;
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
            ->orderBy('word.word', 'ASC')
            ->orderBy('trans_word.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
