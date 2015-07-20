<?php


namespace Main\DefaultBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Form as f;

class ApiUserController extends FOSRestController
{
    /**
     * @Rest\View()
     */
    public function postGetDicAction(Request $request)
    {
        if ($id = $request->request->get('id')) {
            if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($id)) {

                if ($uid = $request->request->get('uid') and
                    $u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->find($uid) and
                    $d->getUser()->getId() == $uid)
                {
                }
                return array('dic' => $d->getJsonArray());
            }
        }
        throw new \Exception('postGetDicAction went wrong!');
    }

    /**
     * @Rest\View()
     */
    public function getScoreAction(Request $request)
    {
        if ($u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->find($request->query->get('uid')) and
            $d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->query->get('did'))
        ) {

            return array('score' => $this->getScore($u, $d));
            ;
        }
    }

    private function getScore($u, $d)
    {
        $a = array('user' => $u, 'dictionary' => $d);
        $ds = $this->getDoctrine()->getRepository('MainDefaultBundle:DictionaryScore')->findOneBy($a);
        
        return $ds->getScore();
    }
}