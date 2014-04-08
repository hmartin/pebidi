<?php


namespace Main\DefaultBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Form as f;

class ApiWordController extends FOSRestController
{


    /**
     * @Rest\View()
     * @Rest\Post("/addWord/{id}")
     */
    public function newWordAction(Request $request, $id)
    {
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
}