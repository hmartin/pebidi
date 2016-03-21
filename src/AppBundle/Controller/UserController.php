<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DictionaryScore;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Dictionary;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @Rest\View()
     */
    public function getAction(Request $request, User $u)
    {
        $request->getSession()->set('petok', $u->getId());

        return $this->getUserAndDic($u);
    }

    /**
     * @ApiDoc(section="User", description="Create user & Dictionary with email",
     *  requirements={
     *      { "name"="email", "dataType"="string", "requirement"="\d+", "description"="Return Email's user" }
     *  },
     * )
     * @Rest\View()
     */
    public function postEmailAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->request->get('email') && filter_var($request->request->get('email'), FILTER_VALIDATE_EMAIL)) {
            $email = $request->request->get('email');

            if ($u = $this->getDoctrine()->getRepository('AppBundle:User')->findOneByEmail($email)) {

            } else {
                $u = new User();
                $u->setEmail($email);
                $u->setUsername($email);
                $u->setPassword($email);
                $u->setRoles(array('ROLE_USER'));
                $em->persist($u);

                $d = new Dictionary();
                $d->setUser($u);
                $d->setLang($request->request->get('destLang'));
                $d->setOriginLang($request->request->get('originLang'));

                $em->persist($d);

                $em->flush();
            }
            $em->refresh($u);
            
            $token = new UsernamePasswordToken($u, null, 'main', $u->getRoles());
            $this->get("security.token_storage")->setToken($token);

            //$event = new InteractiveLoginEvent($request, $token);
            //$this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

            return $this->getUserAndDic($u);
        }
        
        return array( 'error' => 'email invalid');
    }

    protected function getUserAndDic(User $u)
    {
        $params = array();
        if ($d = $u->getDefaultDictionary()) {
            $params['user'] = $this->getDoctrine()->getRepository('AppBundle:User')->getArray($u);
            $params['dic'] =  $this->get('app.dictionary_model')->createJson($d);
            $words = $d->getWords();
            foreach ($words as $w) {
                $params['user']['wids'][] = $w->getId();
            }
        }

        return $params;
    }

    protected function getScore($u, $d)
    {
        $a = array('user' => $u, 'dictionary' => $d);
        $ds = $this->getDoctrine()->getRepository('AppBundle:DictionaryScore')->findOneBy($a);

        return $ds->getScore();
    }
}
