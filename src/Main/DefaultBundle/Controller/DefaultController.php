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
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/d/{id}", name="dictionary" )
     * @Template
     */
    public function dictionaryAction(Request $request, $id)
    {
        $params = array();

        $d = $this->getDoctrine()->getRepository('MainDefaultBundle:dictionary')->find( base_convert($id, 23, 10) );
        $w = new e\Word();
        $t = new e\Translation();
        $w->addTranslation($t);
        $wt = new f\WordType();
        $form = $this->createForm($wt, $w);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $w->addDictionary($d);
            $this->persistAndFlush($w);
            foreach( $w->getTranslations() as $t ) {
                $t->setWord( $w );
                $t->setDictionary($d);
                $this->persistAndFlush($t);
            }
            foreach( $w->getDictionaries() as $d ) {
                $d->addWord( $w );
                $this->persistAndFlush($t);
            }

            return $this->redirect($this->generateUrl('dictionary', array('id' => base_convert($d->getId(), 10, 23)) ));
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
            $this->persistAndFlush($u);
            $d = new e\Dictionary();
            $d->setUser($u);
            $d->setLang('en');
            $this->persistAndFlush($d);

            return $this->redirect($this->generateUrl('dictionary', array('id' => base_convert($d->getId(), 10, 23)) ));
        }

        //return $this->redirect($this->generateUrl('default'));
    }


    private function persistAndFlush($obj) {

        $em = $this->getDoctrine()->getManager();
        $em->persist($obj);
        $em->flush();
    }
}
