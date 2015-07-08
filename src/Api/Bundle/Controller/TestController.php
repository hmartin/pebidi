<?php

namespace Api\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class TestController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Rest\View()
     */
    public function newAction(Request $request)
    {
        echo $request->get('uid').'<br>';
        echo $request->get('id').'<br>';
        $em = $this->getDoctrine();
        if ((null !== ($u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->find($request->get('uid')))) &&
            (null !== ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->get('id')))) &&
            $nb = $request->request->get('nbQuestion'))
        {
            echo 'ii';
            echo $request->get('id');
            $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordsForTest($nb, $d, $u);
            shuffle($results);

            $t = new e\Test();
            $t->setDictionary($d);
            $t->setNbQuestion($nb);
            $t->setUser($u);
            $em->persist($t);
            $em->flush();

            return array('id' => $t->getId(), 'words' => $results);
        }
        throw new \Exception('Something went wrong!');
    }
}
