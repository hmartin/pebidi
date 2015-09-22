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
     * @ApiDoc(section="Dictionary", description="Get groups words list")
     * @Rest\View()
     */
    public function getGroupsWordsAction(Request $request)
    {
        if (null == ($user = $this->getUser())) {
            $user = null;
        }

        $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->getGroupsWords($user);
        $r = array();
        foreach($results as $d) {
            $r[] = $d->getJsonArray();
        }

        return array('groupsWords' => $r);
    }
  
    /**
     * @ApiDoc(section="Dictionary", description="Post Dic to group and new dic",
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

        if ($dGroup = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->request->get('did'))) {
            $dGroup->setGroupWord(1);
            $dGroup->setMain(0);
            $dGroup->setTitle($request->get('title'));
            $dGroup->setDescription($request->get('description'));
            $dGroup->setPrivate($request->get('private'));
            
            $em->persist($dGroup);
              
            $d = new Dictionary();
            $d->setUser($dGroup->getUser());
            $d->setLang($dGroup->getLang());
            $d->setOriginLang($dGroup->getOriginLang());
              
            $em->persist($d);
                
            $em->flush();

            return array('dic' => $d->getJsonArray());
        }
        throw new \Exception('Something went wrong!');
    }

    /**
     * @ApiDoc(section="Dictionary", description="Add group to a dic",
     *  requirements={
     *      { "name"="did", "dataType"="integer", "requirement"="\d+", "description"="dic id" },
     *      { "name"="gid", "dataType"="integer", "requirement"="\d+", "description"="Group (dic) id" }
     *  },
     * )
     * @Rest\View()
     */
    public function postAddGroupWordAction(Request $request)
    {
        if ($gid = $request->request->get('gid') and $did = $request->request->get('did')
            and $gw = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($gid)
            and $d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($did))
        {
            $i = 0;
            foreach($gw->getWords() as $w) {
                if(!$d->getWords()->contains($w)) {
                    $i++;
                    $d->addWord($w);
                }
            }

            $this->get('persist')->persistAndFlush($d);

            return array('dic' => $d->getJsonArray(), 'nbAdd' => $i);
        }
        throw new \Exception('AddGroupWord went wrong!');
    }
}
