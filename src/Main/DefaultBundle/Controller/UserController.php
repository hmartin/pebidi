<?php


namespace Main\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Form as f;

class UserController extends Controller
{
    
    /**
     * @Route("/{_locale}/betaUse", name="betaUse" )
     */
    public function betaUseAction(Request $request)
    {
        if ($email = $request->request->get('email')) {
            
            if ($u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->findOneByEmail($email)) {
                $d = $u->getDefaultDictionary();
                return $this->redirect($this->generateUrl('newWord', array('id' => $d->getConvertId()) ));  
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

            return $this->redirect($this->generateUrl('newWord', array('id' => $d->getConvertId()) ));
        }
        throw new \Exception('Something went wrong!');
    }
}