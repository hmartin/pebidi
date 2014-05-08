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
     * @Route("/", name="_default")
     * @Template()
     */
    public function initAction()
    {
        return $this->redirect($this->generateUrl('default', array( '_locale' => 'fr' )));
    }

    /**
     * @Route("/{_locale}", name="default", requirements={"_locale" = "en|fr|de"} )
     * @Template
     */
    public function indexAction(Request $request)
    {
        //echo '<pre>';
        if ($this->get('cookie')->has('id')) {
            return $this->redirect($this->generateUrl('newWord', array('id' => $this->get('cookie')->get('id')) ));
        }
        $u = $this->getDoctrine()->getRepository('MainDefaultBundle:user')->find(1);
        $d = $this->getDoctrine()->getRepository('MainDefaultBundle:dictionary')->find(1);

        $w = $this->getDoctrine()->getRepository('MainDefaultBundle:word')->find(1);
        $t = $this->getDoctrine()->getRepository('MainDefaultBundle:test')->find(1);
        $test = $this->getDoctrine()->getRepository('MainDefaultBundle:test');
        $p = $this->getDoctrine()->getRepository('MainDefaultBundle:point')->find(1);
        $a = array('uid' => $u->getId(), 'did' => $d->getId());
        $test->getAvgScore($a);

        $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordsForTest(5, $d, $u);
        var_dump($results);
        return array();
    }

    /**
     * @Route("/{_locale}/cc", name="clearCookies" )
     */
    public function clearCookiesAction()
    {
        $this->get('cookie')->remove('id');
        return $this->redirect($this->generateUrl('default'));
    }

    /**
     * @Route("/{_locale}/w/{id}", name="newWord" )
     * @Template
     */
    public function newWordAction(Request $request, $id)
    {
        $params = array();
        $this->get('cookie')->setCookie('id', $id);

        if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:dictionary')->find( base_convert($id, 23, 10) )) 
        {
            if ($d->getPrivate()) {
                if ($u = $this->getUser()) {
                    if ($d->getUser() != $u ) {
                        return $this->redirect($this->generateUrl('static', array('template' => 'private')));
                    }
                } else {
                    return $this->redirect($this->generateUrl('static', array('template' => 'pleaseLogin')));
                }
            }
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
        return $this->redirect($this->generateUrl('default'));
    }

    /**
     * @Route("/{_locale}/s/{template}", name="static" )
     * @Template
     */
    public function staticAction($template)
    {    
        return $this->render('MainDefaultBundle:Static:'.$template.'.html.twig', array());
    }

    public function clean() {
        /*
         SET FOREIGN_KEY_CHECKS=0;
TRUNCATE `DictionariesWord`;
TRUNCATE `Dictionary`;
TRUNCATE `DictionaryScore`;
TRUNCATE `fos_user`;
TRUNCATE `Point`;
TRUNCATE `Test`;
TRUNCATE `Translation`;
        */
    }

}
