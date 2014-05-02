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
        if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find( base_convert($request->request->get('id'), 23, 10) ))
        {
            $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->createQueryBuilder('d')
                ->leftJoin('d.translations', 't')
                ->leftJoin('t.word', 'w')
                ->select('w.id, w.word, t.translation')
                ->where('d.id = :id')
                ->setParameter(':id', $d->getId());


            $results = $qb->getQuery()->getResult();

            $t = new e\Test();
            $t->setDictionary($d);
            $this->get('persist')->persistAndFlush($t);


            return array('id' => $t, 'words' => $results);
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
            foreach($points as $pt) {
                $w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->find($pt['wid']);
                $p = new e\Point();
                $p->setPoint($pt['p']);
                $p->setWord($w);
                $p->setTest($t);
                $this->get('persist')->persistAndFlush($p);

            }

            return array();
        }
        throw new \Exception('Something went wrong!');
    }

}