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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class DictionaryController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @ApiDoc(section="Dictionary", description="Get basic dic info")
     * @Rest\View()
     */
    public function getAction(Request $request, Dictionary $d)
    {
        return $d->getJsonArray();
    }

    /**
     * @ApiDoc(section="Dictionary", description="Get words with transaltion for one dic")
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
     * @ApiDoc(section="Dictionary", description="Get gorups words list")
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
