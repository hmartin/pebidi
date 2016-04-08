<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Point;
use AppBundle\Entity\Test;
use AppBundle\Entity\Result;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ResultController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(section="Result", description="Create result with datetime")
     * @Rest\View()
     */
    public function postUserAction(Request $request, Test $t, User $u)
    {
        $em = $this->getDoctrine()->getManager();

        $r = new Result($t, $u);
        $em->persist($r);
        $em->flush();

        return array('id' => $r->getId());
    }

    /**
     * @ApiDoc(section="Result", description="Save result")
     * @Rest\View()
     */
    public function postSaveAction(Request $request, Result $r)
    {
        $em = $this->getDoctrine()->getManager();
        $points = $request->request->get('points');
        $s = $i = 0;
        foreach ($points as $pt) {
            $w = $this->getDoctrine()->getRepository('AppBundle:Word')->find($pt['wid']);
            $p = new Point();
            $p->setPoint($pt['p']);
            $p->setWord($w);
            $p->setResult($r);
            $em->persist($p);
            $s = $s + $pt['p'];
            $i++;
            
        
        }

        $r->setScore($s * 100 / $i);
        $em->persist($r);

        $em->flush();
        
        $subQuery = 'SELECT SUM(p.point)/COUNT(p.id) FROM Point p ' . 
            'JOIN Result r ON p.result_id = r.id AND r.user_id = ' .  $r->getUser()->getId() .
            ' WHERE p.word_id = dw.word_id ORDER BY r.created DESC LIMIT 5';
        $query = 'UPDATE DictionaryWord dw SET dw.score = ('.$subQuery.');';
        $connection = $em->getConnection();
        $stmt = $connection->prepare($query);
        $stmt->execute();

        $score = $this->getDoctrine()->getRepository('AppBundle:Result')->getAvgScore($r->getUser());

        return ['score' => $score];
    }
}
