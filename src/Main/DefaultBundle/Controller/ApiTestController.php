<?php


namespace Main\DefaultBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Form as f;

class ApiTestController extends FOSRestController
{
    /**
     * @Rest\View()
     */
    public function postSaveResultAction(Request $request)
    {
        $points = $request->request->get('points');
        if ($t = $this->getDoctrine()->getRepository('MainDefaultBundle:Test')->find($request->request->get('id'))) {
            $s = $i = 0;
            foreach ($points as $pt) {
                $w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->find($pt['wid']);
                $p = new e\Point();
                $p->setPoint($pt['p']);
                $p->setWord($w);
                $p->setTest($t);
                $this->get('persist')->persistAndFlush($p);
                $s = $s + $pt['p'];
                $i++;
            }
            $t->setScore($s * 100 / $i);
            $this->get('persist')->persistAndFlush($t);
            $a = array('user' => $t->getUser()->getId(), 'dictionary' => $t->getDictionary()->getId());
            $ds = $this->getDoctrine()->getRepository('MainDefaultBundle:DictionaryScore')->findOneBy($a);
            $a = array('uid' => $t->getUser()->getId(), 'did' => $t->getDictionary()->getId());
            $newScore = $this->getDoctrine()->getRepository('MainDefaultBundle:Test')->getAvgScore($a);
            $ds->setScore($newScore);
            $this->get('persist')->persistAndFlush($ds);

            return array('score' => $newScore);
        }
        throw new \Exception('Something went wrong!');
    }

}