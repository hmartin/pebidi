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
     * @Route("/{_locale}/d/{id}", name="dictionary" )
     * @Template
     */
    public function dictionaryAction(Request $request, $id)
    {
        $params = array();
        //pass user
        $params['words'] = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordsList($id);
        return $params;
    }
}
