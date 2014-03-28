<?php

namespace Main\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Form as f;

class DictionaryController extends Controller
{
    /**
     * @Route("/d/{id}", name="dictionary" )
     * @Template
     */
    public function dictionaryAction(Request $request, $id)
    {
        $params = array();
        $d = $this->getDoctrine()->getRepository('MainDefaultBundle:dictionary')->find( base_convert($id, 23, 10) );
        $params['words'] = $d->getWords();
        return $params;
    }

    private function persistAndFlush($obj) {

        $em = $this->getDoctrine()->getManager();
        $em->persist($obj);
        $em->flush();
    }
}
