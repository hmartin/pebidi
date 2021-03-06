<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Dictionary;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class DictionaryController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @ApiDoc(section="Dictionary", description="Get basic dic info")
     * @Rest\View()
     */
    public function getAction(Request $request, Dictionary $d)
    {
        return $this->get('app.dictionary_model')->createJson($d);
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
            $u = $this->getDoctrine()->getRepository('AppBundle:User')->find($uid);
        }
        $results = $this->getDoctrine()->getRepository('AppBundle:Word')->getDictionaryAllWords($d, $u);

        return $results;
    }

    /**
     * @ApiDoc(section="Dictionary", description="Get groups words list")
     * @Rest\View()
     */
    public function getGroupsWordsAction(Request $request)
    {
        if (null == ($user = $this->getUser())) {
            $user = null;
        }

        $results = $this->getDoctrine()->getRepository('AppBundle:Dictionary')->getGroupsWords($user);
        $r = array();
        foreach($results as $d) {
            $r[] = $this->get('app.dictionary_model')->createJson($d);
        }

        return array('groupsWords' => $r);
    }
  
    /**
     * @ApiDoc(section="Dictionary", description="Transform Dic to group and return new user dic",
     *  requirements={
     *      { "name"="did", "dataType"="integer", "requirement"="\d+", "description"="dic id" },
     *      { "name"="title", "dataType"="string", "requirement"="\d+", "description"="Group title" },
     *      { "name"="description", "dataType"="string", "description"="Group description" },
     *      { "name"="private", "dataType"="bolean", "requirement"="\d+", "description"="private" }
     *  },
     * )
     * @Rest\View()
     */
    public function postCreateGroupAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($dGroup = $this->getDoctrine()->getRepository('AppBundle:Dictionary')->find($request->request->get('did'))) {
            $dGroup->transformToGroup(
                $request->get('title'),
                $request->get('description'),
                $request->get('private')
            );
            
            $em->persist($dGroup);
            
            $d = $this->get('app.dictionary_model')
                    ->createDictionary($dGroup->getUser(), $dGroup->getLang(), $dGroup->getOriginLang());

            return ['dic' =>  $this->get('app.dictionary_model')->createJson($d)];
        }
        throw new \Exception('Something went wrong!');
    }

    /**
     * @ApiDoc(section="Dictionary", description="Add group to a dic",
     *  requirements={
     *      { "name"="did", "dataType"="integer", "requirement"="\d+", "description"="dic id" },
     *      { "name"="gid", "dataType"="integer", "requirement"="\d+", "description"="Group (dic) id" },
     *      { "name"="limit", "dataType"="integer", "requirement"="\d+", "description"="Limit" }
     *  },
     * )
     * @Rest\View()
     */
    public function postAddGroupWordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $limit = $request->request->get('limit');
        if (($gw = $this->getDoctrine()->getRepository('AppBundle:Dictionary')->find($request->request->get('gid')))
            && ($d = $this->getDoctrine()->getRepository('AppBundle:Dictionary')->find($request->request->get('did'))))
        {
            $i = 0;
            $words = $em->getRepository('AppBundle:Word')->getDictionaryAllWords($gw);
            shuffle($words);
            foreach($words as $w) {
                $a[] = $w['id'];
            }
            $groupWids = array_diff($a, $d->getWids());

            foreach($groupWids as $wid) {
                $word = $this->getDoctrine()->getRepository('AppBundle:Word')->find($wid);
                if ($this->get('app.dictionary_word_model')->addWord($d, $word)) {
                    $i++;
                    if ($i >= $limit) {
                        break;
                    }
                }
            }

            $this->get('persist')->persistAndFlush($d);

            return ['dic' => $this->get('app.dictionary_model')->createJson($d), 'nbAdd' => $i];
        }
        throw new \Exception('AddGroupWord went wrong!');
    }
  
    /**
     * @ApiDoc(section="Dictionary", description="Disabled group",
     *  requirements={
     *      { "name"="d", "dataType"="integer", "requirement"="\d+", "description"="dic id" }
     *  },
     * )
     * @Rest\View()
     */
    public function deleteAction(Dictionary $d)
    {
        $em = $this->getDoctrine()->getManager();
        
        $d->setDisabled(1);
        $em->flush();
    }
}
