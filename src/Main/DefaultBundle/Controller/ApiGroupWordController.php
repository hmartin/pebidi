<?php


namespace Main\DefaultBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Entity\Ww;
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
            $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->createQueryBuilder('d');

        } else {
            $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:GroupWord')->createQueryBuilder('d');
        }
        $query ='SELECT  w.id, w.word, ww.word2_id, SUM(p.point) AS word_point FROM Dictionary d
                LEFT JOIN DictionariesWord dw ON d.id = dw.dictionary_id
                LEFT JOIN Word w ON w.id = dw.word_id
               LEFT JOIN Ww ww ON ww.word1_id = w.id OR ww.word2_id = w.id
                LEFT JOIN Point p ON w.id = p.word_id
               WHERE d.id = '.$id .' GROUP BY w.id';
        ;
        $em = $this->getDoctrine();
        $connection = $em->getConnection();
        $stmt = $connection->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();

        if($uid = $request->query->get('uid')) {
            //var_dump($uid);
            $qb
                //->addSelect('SUM(IF(test.id IS NOT NULL, p.point, 0))/SUM(IF(test.id IS NOT NULL, 1, 0)) AS stat_sum_realised')
                ->leftJoin('p.test', 'test', 'WITH', 'test.user = :uid')
                ->setParameter(':uid', $request->query->get('uid'))
            ;
        } else {
            //$qb->addSelect('SUM(p.point)/COUNT(p.id) AS stat_sum_realised');
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