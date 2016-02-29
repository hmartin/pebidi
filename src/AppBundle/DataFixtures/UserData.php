<?php

namespace AppBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class UserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $email = 'suerDAte@userData.com';
        $u = new User();
        $u->setEmail($email);
        $u->setUsername($email);
        $u->setPassword($email);
        $u->setRoles(array('ROLE_USER'));

        $manager->persist($u);
        $manager->flush();
    }
}