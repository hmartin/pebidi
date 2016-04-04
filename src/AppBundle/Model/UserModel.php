<?php

namespace AppBundle\Model;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;

use AppBundle\Entity\Dictionary;

class UserModel
{
    protected $delete = false;
    protected $em;
    protected $tokenStorage;

    public function __construct(EntityManager $em, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }
    
    public function getUser($id = null)
    {
        if ($user = $this->tokenStorage->getToken()->getUser()) {
            if ($id == $user->getId()) {
                return $user;
            }
        } elseif ($id) {
            $user = $this->em->getRepository('AppBundle:User')->find($id);
            
            if (!$user->getPrivate()) {
                //TODO: auto connect
                return $user;
            }
        }
        
        return false;
    }
}
