<?php


namespace Main\DefaultBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Form as f;
use Symfony\Component\DomCrawler\Crawler;

class ApiWordController extends FOSRestController
{
    /**
     * @Rest\View()
     */
    public function getAutoCompleteWordsAction(Request $request)
    {
        $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->createQueryBuilder('w')
            ->select('w.word')
            ->addSelect('LENGTH(w.word) AS l')
            ->where('w.word LIKE :word')
            ->setMaxResults(8)
            ->setParameter(':word', $request->query->get('word') . '%')
            ->orderBy('l', 'ASC');

        $results = $qb->getQuery()->getResult();

        return array('words' => $results);
    }

    /**
     * @Rest\View()
     */
    public function getOriDestWordAction($o, $d, $word)
    {
        $html = \file_get_contents('http://www.dict66.com/translate/fr-en/'.$word);
        //print_r($html);
        $crawler = new Crawler($html);

        $a = $crawler->filter('.translation-results tbody > tr')->each(function ($node, $i) {
            $o = $node->filter('.result-item-source > .wordentry')->each(function ($node, $i) {
                return $node->text();
            });
            $d = $node->filter('.result-item-target > .wordentry')->each(function ($node, $i) {
                return $node->text();
            });
            return array('origin' => $o, 'dest' => $d);
        });
        return $a;
    }

}