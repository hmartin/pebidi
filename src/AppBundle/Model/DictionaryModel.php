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
    
    public function createDictionary($user, $lang, $originLang)
    {
        $d = new Dictionary();

        $d->setMain(1);
        $d->setUser($user);
        $d->setLang($lang);
        $d->setOriginLang($originLang);
        $this->em->persist($d);

        $this->em->flush();
        $d->setSlug($d->getId());
        $this->em->flush();
        
        return $d;
    }
}
