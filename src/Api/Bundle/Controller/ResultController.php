<?php

namespace Api\Bundle\Controller;

use Main\DefaultBundle\Entity\Point;
use Main\DefaultBundle\Entity\Test;
use Main\DefaultBundle\Entity\Result;
use Main\DefaultBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ResultController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(section="Result" , description="Save results")
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
     * @Rest\View()
     */
    public function postSaveAction(Request $request, Result $r)
    {
        $em = $this->getDoctrine()->getManager();
        $points = $request->request->get('points');
        $s = $i = 0;
        foreach ($points as $pt) {
            $w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->find($pt['wid']);
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

        $score = $this->getDoctrine()->getRepository('MainDefaultBundle:Result')->getAvgScore($r->getUser());

        $params = array('user' => array('id' => $r->getUser()->getId(), 'score' => $score));

        return $params;
    }
}
