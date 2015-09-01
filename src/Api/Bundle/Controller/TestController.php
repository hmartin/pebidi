<?php

namespace Api\Bundle\Controller;

use Main\DefaultBundle\Entity\Result;
use Main\DefaultBundle\Entity\Test;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class TestController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Rest\View()
     */
    public function getAction(Request $request, Test $t) 
    {
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

        if ((null !== ($u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->find($request->get('uid')))) &&
            (null !== ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->get('id')))) &&
            $nb = $request->get('nbQuestion')
        ) {
            $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordsForTest($nb, $d, $u);
        }
        shuffle($results);

        $t = new Test();
        $t->setCreator($u);
        $t->setDictionary($d);
        foreach($results as $k => $w) {

            $t->addWord($w['object']);
            unset($results[$k]['object']);
        }
        $em->persist($t);

        $r = new Result($t ,$u);
        $em->persist($r);

        $em->flush();

        return array('id' => $t->getId(),'rid' => $r->getId(), 'words' => $results);
    }
}
