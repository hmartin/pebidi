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
                ->select('g.id, g.title, g.description')
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
    public function getTypeWordsListAction($type, $id, Request $request)
    {
        if ($type == 'dictionary') {
            $id = base_convert($id, 23, 10);
            $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->createQueryBuilder('d');

        } else {
            $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:GroupWord')->createQueryBuilder('d');
        }

        $qb->leftJoin('d.words', 'w')
        ->select('w.id, w.word ')
        ->where('d.id = :id')
        ->setParameter(':id', $id);

        $results = $qb->getQuery()->getResult();

        return array('words' => $results);
    }

    
    /**
     * @Rest\View()
     */
    public function postAddGroupWordAction(Request $request)
    {
        if ($gwid = $request->request->get('gwid') and $did = $request->request->get('did')
            and $gw = $this->getDoctrine()->getRepository('MainDefaultBundle:GroupWord')->find($gwid)
            and $d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($did))
        {

            $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->createQueryBuilder('w')
                ->leftJoin('w.dictionaries','d')
                ->leftJoin('w.groupsWords','gw')
                ->where('d.id  = :did')
                ->andWhere('gw.id  = :gwid')
                ->setParameter('gwid', $gwid)
                ->setParameter('did', $did)
            ;

            $results = $qb->getQuery()->getResult();
            foreach($results as $r) {
                $excludeWord[] = $r->getId();
                var_dump($r->getWord());
            }
            $i=0;
            foreach($gw->getWords() as $w) {
                if (!in_array($w->getId(), $excludeWord)) {
                    $d->addWord($w);
                    $i++;
                }
            }
            $this->get('persist')->persistAndFlush($d);

            return array('nbAdd' => $i);
        }
        throw new \Exception('AddGroupWord went wrong!');
    }
}