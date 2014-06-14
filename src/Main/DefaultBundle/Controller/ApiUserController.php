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
    public function postEmailAction(Request $request)
    {
        if ($email = $request->request->get('email')) {

            if ($u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->findOneByEmail($email)) {

            } else {
                $u = new e\User();
                $u->setEmail($email);
                $u->setUsername($email);
                $u->setPassword($email);
                $this->get('persist')->persistAndFlush($u);
            }
            $params = array('uid' => $u->getId());
            if ($d = $u->getDefaultDictionary()) {
                $params['dic'] = $d->getJsonArray();
            }
            return $params;
        }
        throw new \Exception('Something went wrong!');
    }

    /**
     * @Rest\View()
     */
    public function postCreateDicAction(Request $request)
    {
        if ($id = $request->request->get('uid')) {
            if ($u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->find($id)) {
                $d = new e\Dictionary();
                $d->setUser($u);
                $d->setLang($request->request->get('destLang'));
                $d->setOriginLang($request->request->get('originLang'));
                $this->get('persist')->persistAndFlush($d);
                $ds = new e\DictionaryScore();
                $ds->setUser($u);
                $ds->setDictionary($d);
                $this->get('persist')->persistAndFlush($ds);

                return array('dic' => $d->getJsonArray());
            }
        }
        throw new \Exception('Something went wrong!');
    }

    /**
     * @Rest\View()
     */
    public function postGetDicAction(Request $request)
    {
        if ($id = $request->request->get('id')) {
            if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($id)) {

                if ($uid = $request->request->get('uid') and
                    $u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->find($uid)
                ) {
                    $d->setUserScore($this->getScore($u, $d));
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