<?php

namespace Api\Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use Main\DefaultBundle\Entity\Word;

class WordController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Rest\View()
     */
    public function getAction(Request $request, Word $w)
    {
       $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordFullTranslation($w);
      
       return array($results);
    }
  
    /**
     * @Rest\View()
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->request->get('id'))) {
            $word = $request->request->get('word');

            if (!$w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => $word['w']))) {
                $w = new Word();
                $w->setWord($word['w']);
                $w->setLocal('en');
                $em->persist($w);
                $em->flush();
            }
            if (!$d->getWords()->contains($w)) {
                $d->addWord($w);
                $em->persist($d);
            }
          
            $em->flush();

            return array('dic' => $d->getJsonArray());
        }
        throw new \Exception('Something went wrong!');
    }

    /**
     * @Rest\View()
     */
    public function removeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->find($request->request->get('id')) and
            $d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->request->get('did'))
        ) {
            $d->getWords()->removeElement($w);
            $em->persist($d);
            $em->flush();
          
            return array('dic' => $d->getJsonArray());
        }
        throw new \Exception('postDeleteWordAction went wrong!');
    }
}