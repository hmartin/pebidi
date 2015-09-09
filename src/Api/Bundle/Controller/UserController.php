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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UserController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @Rest\View()
     */
    public function getAction(Request $request, User $u)
    {
      return $this->getUserAndDic($u);
    }

    /**
     * @ApiDoc(section="User", description="Create user & Dictionary with email",
     *  requirements={
     *      { "name"="email", "dataType"="string", "requirement"="\d+", "description"="word id" }
     *  },
     * )
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
         
            return $this->getUserAndDic($u);
        }
    }
  
    protected function getUserAndDic(User $u) 
    {
        $score = $this->getDoctrine()->getRepository('MainDefaultBundle:Result')->getAvgScore($u);
        $params = array('user' => array('id' => $u->getId(), 'score' => $score));
      
        if ($d = $u->getDefaultDictionary()) {
            $params['dic'] = $d->getJsonArray();
            $params['user']['did'] = $d->getId();
            $words = $d->getWords();
            foreach($words as $w) {
                $params['user']['wids'][] = $w->getId();
            }
        }

        return $params;
      
    }

    protected function getScore($u, $d)
    {
        $a = array('user' => $u, 'dictionary' => $d);
        $ds = $this->getDoctrine()->getRepository('MainDefaultBundle:DictionaryScore')->findOneBy($a);
        
        return $ds->getScore();
    }
}
