<?php

namespace Main\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Form as f;

class DefaultController extends Controller
{


    /**
     * @Route("/", name="default" )
     * @Template
     */
    public function indexAction(Request $request)
    {
        if ($this->get('cookie')->has('id')) {
            return $this->redirect($this->generateUrl('newWord', array('id' => $this->get('cookie')->get('id')) ));
        }
        return array();
    }

    /**
     * @Route("/cc", name="clearCookies" )
     */
    public function clearCookiesAction()
    {
        $this->get('cookie')->remove('id');
        return $this->redirect($this->generateUrl('default'));
    }

    /**
     * @Route("/w/{id}", name="newWord" )
     * @Template
     */
    public function newWordAction(Request $request, $id)
    {
        $params = array();
        $this->get('cookie')->setCookie('id', $id);

        $d = $this->getDoctrine()->getRepository('MainDefaultBundle:dictionary')->find( base_convert($id, 23, 10) );
        $w = new e\Word();
        $t = new e\Translation();
        $w->addTranslation($t);
        $wt = new f\WordType();
        $form = $this->createForm($wt, $w);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $w->addDictionary($d);
            $this->get('persist')->persistAndFlush($w);
            foreach( $w->getTranslations() as $t ) {
                $t->setWord( $w );
                $t->setDictionary($d);
                $this->get('persist')->persistAndFlush($t);
            }
            foreach( $w->getDictionaries() as $d ) {
                $d->addWord( $w );
                $this->get('persist')->persistAndFlush($t);
            }

            return $this->redirect($this->generateUrl('newWord', array('id' => $d->getConvertId()) ));
        }
        $params['dictionary'] = $d;
        $params['form'] = $form->createView();

        return $params;
    }

    /**
     * @Route("/betaUse", name="betaUse" )
     */
    public function betaUseAction(Request $request)
    {
        if ($request->request->get('email')) {
            //todo test email, test dictionary if create one, else redirect dictionary
            $u = new e\User();
            $u->setEmail($request->request->get('email'));
            $u->setUsername($request->request->get('email'));
            $u->setPassword($request->request->get('email'));
            $this->get('persist')->persistAndFlush($u);
            $d = new e\Dictionary();
            $d->setUser($u);
            $d->setLang('en');

            $this->get('persist')->persistAndFlush($d);
            $request->getSession()->set('id', $d->getConvertId());

            return $this->redirect($this->generateUrl('newWord', array('id' => $d->getConvertId()) ));
        }

        return $this->redirect($this->generateUrl('default'));
    }

}
