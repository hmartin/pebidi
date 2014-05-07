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
                return array('dic' => $d->getJsonArray());
            }
        }
        throw new \Exception('Something went wrong!');
    }
}