<?php


namespace Main\DefaultBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Form as f;


class ApiGroupWordController extends FOSRestController
{
    /**
     * @Rest\View()
     */
    public function groupWordAction(Request $request)
    {
        if ($l = $request->query->get('lang'))
        {
            $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:GroupWord')->createQueryBuilder('g')
                ->select('d.id, g.title, g.description')
                ->where('g.lang = :lang')
                ->setParameter('lang', $l);

            $results = $qb->getQuery()->getResult();

            return array('groups' => $results);
        }
        throw new \Exception('groupWord went wrong!');
    }
    
    /**
     * @Rest\View()
     */
    public function postAddGroupWordAction(Request $request)
    {
        if ($gw = $this->getDoctrine()->getRepository('MainDefaultBundle:GroupWord')->find($request->query->get('wgid'))
            and $d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->query->get('did'))) 
        {
            
            $d->setWords(array_merge($gw->getWords(), $d->getWords()));           
            $this->get('persist')->persistAndFlush($t);

            return array();
        }
        throw new \Exception('AddGroupWord went wrong!');
    }
}