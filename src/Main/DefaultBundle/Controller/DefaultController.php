<?php

namespace Main\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Form as f;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Main\DefaultBundle\Entity\Test;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_default")
     * @Template()
     */
    public function initAction()
    {
        return $this->redirect($this->generateUrl('default', array('_locale' => 'fr')));
    }

    /**
     * @Route("/{_locale}", name="default", requirements={"_locale" = "en|fr|de"} )
     * @Template
     */
    public function indexAction(Request $request)
    {
        //echo '<pre>';
        //$u = $this->getDoctrine()->getRepository('MainDefaultBundle:user')->find(1);
        //$d = $this->getDoctrine()->getRepository('MainDefaultBundle:dictionary')->find(1);

        $w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordsForTest(2, 2, 2);
        $em = $this->getDoctrine()->getManager();

        if ((null !== ($u = $this->getDoctrine()->getRepository('MainDefaultBundle:User')->find(2))) &&
            (null !== ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find(2))) &&
            $nb = 2
        ) {
            $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordsForTest($nb, $d, $u);
        }
        dump($results);
        shuffle($results);

        $t = new Test();
        $t->setCreator($u);
        foreach ($results as $w) {
            $t->addWord($w['object']);
        }
        $em->persist($t);
        $em->flush();
        $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordsForSameTest(1);
        /*
                SET FOREIGN_KEY_CHECKS=0;
                TRUNCATE Word;
                TRUNCATE WordWord;
                SET FOREIGN_KEY_CHECKS=1;

                        $f = file_get_contents('http://persodic-local.com/dict4.json');
                        $a = json_decode($f);
                        \Doctrine\Common\Util\Debug::dump($a[100]->w,3);
                        $i=0;
                        foreach($a as $w) {
                            $ww = new e\Word();
                            $ww->setWord($w->w);
                            $ww->setLang('en');
                            $this->get('persist')->persist($ww);
                            foreach(explode(',', $w->t) as $t) {
                                $tt = new e\Word();
                                $tt->setWord(trim($t));
                                $tt->setLang('fr');
                                $this->get('persist')->persist($tt);
                                $wwe = new e\WordWord();
                                $wwe->setWord1($ww);
                                $wwe->setWord2($tt);
                                $this->get('persist')->persist($wwe);
                            }
                            //echo $i.'<br>';
                            $i++;
                            if ($i % 1000 == 0 ) {
                                $this->get('persist')->flush();

                            }
                        }
                        $this->get('persist')->flush();

                        $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->createQueryBuilder('w');

                        $qb
                            ->select('w.id, w.word, www1.word, www2.word')
                            ->innerJoin('w.wordwords1', 'ww1')
                            ->innerJoin('w.wordwords2', 'ww2')
                            ->innerJoin('ww1.word2', 'www1')
                            ->innerJoin('ww2.word1', 'www2')
                            ->where('ww1.id != w.id AND ww2.id != w.id');

                        $results = $qb->getQuery()->getResult();
                        foreach ($results as $r) {
                            var_dump($r);
                        }

        */
        return $this->render('MainDefaultBundle:Default:index.html.twig', array());
    }

    /**
     * @Route("/{_locale}/json", name="generateJson" )
     */
    public function generateJsonAction()
    {
        $query = 'SELECT  w.id, w.word as w, w2.word as t FROM Word w
               JOIN Ww ww ON ww.word1_id = w.id
               JOIN Word w2 ON ww.word2_id = w2.id
               WHERE w.local = "en" GROUP BY w.id ORDER BY ww.priority ';
        $em = $this->getDoctrine();
        $connection = $em->getConnection();
        $stmt = $connection->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll();
        $file = fopen(__DIR__ . '/../../../../web/dict/dict.json', "w");
        echo fwrite($file, json_encode($results));
        fclose($file);
    }

    public function clean()
    {
        /*
         SET FOREIGN_KEY_CHECKS=0;
        TRUNCATE `DictionariesWord`, `Dictionary`, `DictionaryScore`, `fos_user`, `Test`, `TestWord`;
         SET FOREIGN_KEY_CHECKS=1;
        */
    }

}
