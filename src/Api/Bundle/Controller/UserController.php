<?php

namespace Api\Bundle\Controller;

use Main\DefaultBundle\Entity\DictionaryScore;
use Main\DefaultBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Main\DefaultBundle\Entity\Dictionary;

class UserController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Rest\View()
     */
    public function postEmailAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

       if ($email = $request->request->get('email')) {

            if ($u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->findOneByEmail($email)) {

            } else {
                $u = new User();
                $u->setEmail($email);
                $u->setUsername($email);
                $u->setPassword($email);
                $em->persist($u);
              
                $d = new Dictionary();
                $d->setUser($u);
                $d->setLang($request->request->get('destLang'));
                $d->setOriginLang($request->request->get('originLang'));
              
                $em->persist($d);
              
                $ds = new DictionaryScore();
                $ds->setUser($u);
                $ds->setDictionary($d);
                $em->persist($ds);
              
                $em->flush();
            }
            $em->refresh($u);
            $d->setUserScore($this->getScore($u, $d));
            $params = array('uid' => $u->getId());
            if ($d = $u->getDefaultDictionary()) {
                $d->setUserScore($this->getScore($u, $d));
                $params['dic'] = $d->getJsonArray();
            }
            return $params;
        }
    }

    private function getScore($u, $d)
    {
        $a = array('user' => $u, 'dictionary' => $d);
        $ds = $this->getDoctrine()->getRepository('MainDefaultBundle:DictionaryScore')->findOneBy($a);
        
        return $ds->getScore();
    }
}
