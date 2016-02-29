<?php

namespace AppBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Main\DefaultBundle\Entity\Word;

class WordData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new Word();
        $userAdmin->setWord('admin');
        $userAdmin->setLocal('en');

        $manager->persist($userAdmin);
        $manager->flush();
    }
}