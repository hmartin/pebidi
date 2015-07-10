<?php


namespace Api\Bundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Form as f;

class WordController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Rest\View()
     */
    public function postAction(Request $request)
    {
        if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->request->get('id'))) {
            $word = $request->request->get('word');

            if (!$w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => $word['w']))) {
                $w = new e\Word();
                $w->setWord($word['w']);
                $w->setLocal('en');
                $this->get('persist')->persistAndFlush($w);
            }
            if (!$d->getWords()->contains($w)) {
                $d->addWord($w);
                $this->get('persist')->persistAndFlush($d);
            }

            return array('dic' => $d->getJsonArray());
        }
        throw new \Exception('Something went wrong!');
    }

    /**
     * @Rest\View()
     */
    public function removeAction(Request $request)
    {
        if ($w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->find($request->request->get('id')) and
            $d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->request->get('did'))
        ) {
            $d->getWords()->removeElement($w);
            $this->get('persist')->persistAndFlush($d);
            return array('dic' => $d->getJsonArray());
        }
        throw new \Exception('postDeleteWordAction went wrong!');
    }
}