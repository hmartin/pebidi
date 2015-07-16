<?php

namespace Api\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Main\DefaultBundle\Entity\Dictionary;

class DictionaryController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @Rest\View()
     */
    public function getAction(Request $request, Dictionary $d)
    {
        return $d->getJsonArray();
    }

    /**
     * @Rest\View()
     */
    public function getWordsAction(Request $request, Dictionary $d)
    {
        $u = null;
        if ($uid = $request->query->get('uid'))
        {
            $u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->find($uid);
        }
        $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getDictionaryAllWords($d, $u);

        return $results;
    }

    /**
     * @Rest\View()
     */
    public function getGroupsWordsAction(Request $request)
    {
        $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->getGroupsWords();
        $r = array();
        foreach($results as $d) {
            $r[] = $d->getGroupWordJsonArray();
        }

        return array('groupsWords' => $r);
    }
}
