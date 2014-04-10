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
    public function postEmailOnlyAction(Request $request)
    {
        if ($email = $request->request->get('email')) {
            
            if ($u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->findOneByEmail($email)) {
                $d = $u->getDefaultDictionary();
                return array('dic' => $d);
            }
            
            $u = new e\User();
            $u->setEmail($email);
            $u->setUsername($email);
            $u->setPassword($email);
            $this->get('persist')->persistAndFlush($u);
            // create personal
            $d = new e\Dictionary();
            $d->setUser($u);
            $d->setLang('en');

            $this->get('persist')->persistAndFlush($d);
            $request->getSession()->set('id', $d->getConvertId());

            return array('dic' => $d);
        }
        throw new \Exception('Something went wrong!');
    }
}