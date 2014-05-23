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
    public function postNewWordAction(Request $request)
    {

        if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find( base_convert($request->request->get('id'), 23, 10) ))
        {
            if (!$w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => $request->request->get('word')))) {
                $w = new e\Word();
                $w->setWord( $request->request->get('word') );
                $w->setLang('en');
                $this->get('persist')->persistAndFlush($w);
            }
            $d->addWord($w);

            $this->get('persist')->persistAndFlush($d);
            $t = new e\Translation();
            $t->setDictionary($d);
            $t->setTranslation( $request->request->get('translation') );
            $t->setWord($w);
            $this->get('persist')->persistAndFlush($t);

            return array('dic' => $d->getJsonArray());
        }
        throw new \Exception('Something went wrong!');
    }
   
    /**
     * @Rest\View()
     */
    public function postWordsAction(Request $request)
    {
        if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find( base_convert($request->request->get('id'), 23, 10) ))
        {
            $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->createQueryBuilder('d')
                ->leftJoin('d.words', 'w')
                ->leftJoin('d.translations', 't', 'WITH', 't.word = w.id')
                ->select('w.id, w.word, t.translation')
                ->where('d.id = :id')
                ->setParameter(':id', $d->getId());

            $results = $qb->getQuery()->getResult();

            return array('words' => $results);
        }
        throw new \Exception('Something went wrong!');
    }


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
            ->setParameter(':word', $request->query->get('word').'%')
            ->orderBy('l', 'ASC');

        $results = $qb->getQuery()->getResult();

        return array('words' => $results);
    }
   
    /**
     * @Rest\View()
     */ 
    public function getOriDestWordAction($o, $d, $word) {
        $html = \file_get_contents('http://www.dict66.com/translate/fr-en/car');
        //var_dump($html);echo '<br><br><br>';
        $crawler = new Crawler($html);

        $a = $crawler->filter('.translation-results tbody > tr')->each(function ($node, $i)
        {
            $o = $node->filter('.result-item-source > .wordentry')->each(function ($node, $i) {
                return $node->text();
            });
            $d = $node->filter('.result-item-target > .wordentry')->each(function ($node, $i) {
                return $node->text();
            });
            return array('origin' => $o, 'dest' => $d);
        });        
    }

}