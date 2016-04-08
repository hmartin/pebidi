<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Result;
use AppBundle\Entity\Test;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class TestController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(section="Test", description="Get word from a test")
     * @Rest\View()
     */
    public function getAction(Request $request, Test $t) 
    {
        $results = $this->getDoctrine()->getRepository('AppBundle:Word')->getWordsForSameTest($t);
        
        shuffle($results);

        return array('id' => $t->getId(), 'words' => $results);      
    }
  
    /**
     * @ApiDoc(section="Test", description="Create test",
     *  requirements={
     *      { "name"="uid", "dataType"="integer", "requirement"="\d+", "description"="User id" },
     *      { "name"="id", "dataType"="integer", "requirement"="\d+", "description"="Dictoinary id" },
     *      { "name"="nbQuestion", "dataType"="integer", "requirement"="\d+", "description"="Number of words" }
     *  },
     * )
     * @Rest\View()
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$request->get('uid') || !($u = $em->getRepository('AppBundle:User')->find($request->get('uid')))) {
            $u = null;
        }
        
        $d = $em->getRepository('AppBundle:Dictionary')->find($request->get('id'));
        $nb = $request->get('nbQuestion');
            
        $results = $this->getDoctrine()->getRepository('AppBundle:Word')->getWordsForTest($nb, $d, $u);
            
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
