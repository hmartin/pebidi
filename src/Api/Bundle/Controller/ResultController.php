<?php

namespace Api\Bundle\Controller;

use Main\DefaultBundle\Entity\Test;
use Main\DefaultBundle\Entity\Result;
use Main\DefaultBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class ResultController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Rest\View()
     */
    public function postUserAction(Request $request, Test $t, User $u)
    {
        $em = $this->getDoctrine()->getManager();
      
        $r = new Result();
        $r->setTest($t);
        $r->setUser($u);
        $em->persist($r);
        $em->flush();

        return array('id' => $r->getId());
    }
}
