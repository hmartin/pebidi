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
    public function postCreateTestAction(Request $request)
    {
        if ($u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->find($request->request->get('uid')) and
            $d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find( base_convert($request->request->get('id'), 23, 10) ) and
            $nb = $request->request->get('nbQuestion'))
        {
            $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordsForTest($nb, $d, $u);

            $t = new e\Test();
            $t->setDictionary($d);
            $t->setNbQuestion($nb);
            $t->setUser($u);
            $this->get('persist')->persistAndFlush($t);

            return array('id' => $t->getId(), 'words' => $results);
        }
        throw new \Exception('Something went wrong!');
    }
    
    /**
     * @Rest\View()
     */
    public function postSaveResultAction(Request $request)
    {
        $points = $request->request->get('points');
        if ($t = $this->getDoctrine()->getRepository('MainDefaultBundle:Test')->find($request->request->get('id')))
        {
            $s = $i = 0;
            foreach($points as $pt) {
                $w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->find($pt['wid']);
                $p = new e\Point();
                $p->setPoint($pt['p']);
                $p->setWord($w);
                $p->setTest($t);
                $this->get('persist')->persistAndFlush($p);
                $s = $s + $pt['p'];$i++;
            }
            $t->setScore($s*100/$i);
            $this->get('persist')->persistAndFlush($t);
            $a = array('user' => $t->getUser()->getId(), 'dictionary' => $t->getDictionary()->getId());
            $ds = $this->getDoctrine()->getRepository('MainDefaultBundle:DictionaryScore')->findOneBy($a);
            $a = array('uid' => $t->getUser()->getId(), 'did' => $t->getDictionary()->getId());
            $ds->setScore($this->getDoctrine()->getRepository('MainDefaultBundle:Test')->getAvgScore($a));
            $this->get('persist')->persistAndFlush($ds);

            return array();
        }
        throw new \Exception('Something went wrong!');
    }

}