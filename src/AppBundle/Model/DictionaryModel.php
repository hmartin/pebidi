<?php

namespace AppBundle\Model;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\Dictionary;

class DictionaryModel
{
    protected $delete = false;
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createJson(Dictionary $d)
    {
        $a = $d->getJsonArray();

        // TODO : select count
        $results = $this->em->getRepository('AppBundle:Word')->getDictionaryAllWords($d);;

        $a['countWord'] = count($results);
        
        return $a;
    }
}