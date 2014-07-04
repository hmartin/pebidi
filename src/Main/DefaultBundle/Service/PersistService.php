<?php

namespace Main\DefaultBundle\Service;

use Doctrine\ORM\EntityManager;

class PersistService
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function persistAndFlush($obj) {
        $this->em->persist($obj);
        $this->em->flush();
    }

    public function persist($obj) {
        $this->em->persist($obj);
    }

    public function flush() {
        $this->em->flush();
    }
}