<?php

namespace AppBundle\Model;

use AppBundle\Entity\Word;
use AppBundle\Entity\DictionaryWord;
use Doctrine\ORM\EntityManager;

use AppBundle\Entity\Dictionary;

class DictionaryWordModel
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function addWord(Dictionary $d, Word $word)
    {
        if (!in_array($word->getId(), $d->getWids())) {
            $dw = new DictionaryWord($d, $word);
            $this->em->persist($dw);

            return true;
        }

        return false;
    }
}