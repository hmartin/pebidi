<?php

namespace Main\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DictionaryRepository extends EntityRepository
{
    public function getGroupsWords()
    {
        return $this->findBy(array('groupWord' => 1));
    }

}
