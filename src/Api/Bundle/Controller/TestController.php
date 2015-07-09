<?php

namespace Api\Bundle\Controller;

use Main\DefaultBundle\Entity\Test;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class TestController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Rest\View()
     */
    public function getAction(Request $request, Test $test) 
    {
        $t = $this->getDoctrine()->getRepository('MainDefaultBundle:Test')->find($id)))) {
        $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordsForSameTest($t);
        
        shuffle($results);

        return array('id' => $t->getId(), 'words' => $results);
      
    }
  
    /**
     * @Rest\View()
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->get('type') == 'new' && (null !== ($u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->find($request->get('uid')))) &&
            (null !== ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->get('id')))) &&
            $nb = $request->get('nbQuestion')
        ) {
            $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordsForTest($nb, $d, $u);
        }
        shuffle($results);

        $t = new Test();
        $t->setCreator($u);
        
        $em->persist($t);
        $em->flush();

        return array('id' => $t->getId(), 'words' => $results);
    }
}
