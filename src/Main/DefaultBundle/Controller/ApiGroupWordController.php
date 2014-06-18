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
        $qb
            ->select('w.id, w.word, t.translation')
            ->addSelect('SUM(p.point)/COUNT(p.id) AS global')
            ->addSelect('GROUP_CONCAT(DISTINCT def.definition) AS definitions')
            ->innerJoin('d.words', 'w')
            ->leftJoin('w.translations', 't')
            ->leftJoin('w.definitions', 'def')
            ->leftJoin('w.points','p')
            ->where('d.id = :id')
        ;

        if($uid = $request->query->get('uid')) {
            //var_dump($uid);
            $qb
                ->addSelect('SUM(IF(test.id IS NOT NULL, p.point, 0))/SUM(IF(test.id IS NOT NULL, 1, 0)) AS stat_sum_realised')
                ->leftJoin('p.test', 'test', 'WITH', 'test.user = :uid')
                ->setParameter(':uid', $request->query->get('uid'))
            ;
        } else {
            $qb
                ->addSelect('SUM(p.point)/COUNT(p.id) AS stat_sum_realised')
            ;
        }

        $qb
            ->setParameter(':id', $id)
            ->groupBy('w.word')
        ;


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

            foreach($gw->getWords() as $w) {
                if(!$d->getWords()->contains($w)) {
                    $d->addWord($w);
                }
            }

            $this->get('persist')->persistAndFlush($d);

            return array('dic' => $d->getJsonArray());
        }
        throw new \Exception('AddGroupWord went wrong!');
    }
}