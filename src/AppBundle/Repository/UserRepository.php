<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\User;

class UserRepository extends EntityRepository
{
    public function getArray(User $u)
    {
        $d = $u->getDefaultDictionary();

        $score = $this
            ->getEntityManager()->getRepository('AppBundle:Result')->getAvgScore($u);

        return array('id' => $u->getId(), 'score' => $score,  'dic' => $d->getJsonArray());
    }
}
