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

        $w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordsForTest(2,1,1);
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
     * @Route("/{_locale}/test", name="test", requirements={"_locale" = "en|fr|de"} )
     * @Template
     */
    public function testAction(Request $request)
    {
        //http://www.wordreference.com/2012/sitemap.aspx?dict=enfr&page=1
        $html = \file_get_contents('http://www.dict66.com/translate/fr-en/car');
        //var_dump($html);echo '<br><br><br>';
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
        echo '<pre><br><br><br>';
        var_dump($a);
        exit;
    }

    /**
     * @Route("/suckWordref", name="suckWordref")
     * @Template()
     */
    public function suckWordrefAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
var_dump(set_time_limit ( 60*60));
        $connection = $em->getConnection();
        $statement = $connection->prepare("
         SET FOREIGN_KEY_CHECKS=0;
TRUNCATE `Word`;
TRUNCATE `Ww`;
TRUNCATE `Sense`;
TRUNCATE `WwSenses`;
         SET FOREIGN_KEY_CHECKS=1;");
//        $statement->execute();

        $ss = $this->getDoctrine()->getRepository('MainDefaultBundle:Suck')->findAll();
        foreach ($ss as $k => $s) {
            if ($k < 2998) {
                continue;
            }
            if ($k > 55000) {
                $em->flush();
                $x = 1 / 0;
                exit;
            }
            $newWord = false;
            echo '<br><br>---------------         ' . $s->getUrl() . '    ------------------------------<br>';
            $crawler = new Crawler($s->getHmtl());
            $crawler = $crawler->filter('table.WRD > tr');
            $class = '';
            $k = 0;
            foreach ($crawler as $domElement) {
                if ($domElement->getAttribute('class') == 'even' || $domElement->getAttribute('class') == 'odd') {
                    $tr = new Crawler($domElement);
                    if ($class != $domElement->getAttribute('class')) {
                        $k = $k + 0.1;
                        $priority = 0;
                        $class = $domElement->getAttribute('class');

                        if (!$newWord) {
                            if (null !== ($newWord = $tr->filter('strong')->eq(0)->html())) {
                                if ($this->getDoctrine()->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => utf8_encode($newWord), 'local' => 'en'))) {
                                    $w = null;
                                    continue;
                                }
                                $w = new e\Word();
                                $w->setLocal('en');
                                $w->setWord(utf8_encode($newWord));
                                $em->persist($w);

                            } else {
                                echo 'error' . $s->getUrl() . '<br>';
                            }
                        }

                        if ((null !== ($senseValue = $tr->filter('td')->eq(1))) && count($senseValue) > 0) {

                            $sense = new e\Sense();
                            $sense->setSense(utf8_encode($senseValue->html()));
                            $sense->setLocal('en');

                            $em->persist($sense);

                        }

                    }
                    if (!is_null($w) && null !== ($trans = $tr->filter('td.ToWrd')->eq(0))) {
                        $trans->filter('em')->each(function (Crawler $crawler) {
                            foreach ($crawler as $node) {
                                $node->parentNode->removeChild($node);
                            }
                        });
                        $trans->filter('a')->each(function (Crawler $crawler) {
                            foreach ($crawler as $node) {
                                $node->parentNode->removeChild($node);
                            }
                        });
                        if (count($trans)) {
                            $priority = $priority + 1;
                            $prior = $priority + $k;
                            echo 'c:' . $class . '   t:' . $trans->html() . ' $prior:' . $prior . '<br>';
                            $tw = new e\Word();
                            $tw->setLocal('fr');
                            $tw->setWord(utf8_decode($trans->html()));
                            $em->persist($tw);
                            $ww = new e\Ww();
                            $ww->setWord1($w);
                            $ww->setWord2($tw);
                            $ww->addSense($sense);
                            $ww->setPriority($prior);

                            $em->persist($ww);
                        }


                    }
                }
            }
        }
        $em->flush();
        $x = 1 / 0;
        exit;
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
        $file = fopen(__DIR__ . '/../../../../web/dict/dictNew.json', "w");
        echo fwrite($file, json_encode($results));
        fclose($file);
        //exit;
    }

    /**
     * @Route("/{_locale}/cc", name="clearCookies" )
     */
    public function clearCookiesAction()
    {
        $this->get('cookie')->remove('id');
        return $this->redirect($this->generateUrl('default'));
    }

    /**
     * @Route("/{_locale}/w/{id}", name="newWord" )
     * @Template
     */
    public function newWordAction(Request $request, $id)
    {
        $params = array();
        $this->get('cookie')->setCookie('id', $id);

        if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:dictionary')->find(base_convert($id, 23, 10))) {
            if ($d->getPrivate()) {
                if ($u = $this->getUser()) {
                    if ($d->getUser() != $u) {
                        return $this->redirect($this->generateUrl('static', array('template' => 'private')));
                    }
                } else {
                    return $this->redirect($this->generateUrl('static', array('template' => 'pleaseLogin')));
                }
            }
            $w = new e\Word();
            $t = new e\Translation();
            $w->addTranslation($t);
            $wt = new f\WordType();
            $form = $this->createForm($wt, $w);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $w->addDictionary($d);
                $this->get('persist')->persistAndFlush($w);
                foreach ($w->getTranslations() as $t) {
                    $t->setWord($w);
                    $t->setDictionary($d);
                    $this->get('persist')->persistAndFlush($t);
                }
                foreach ($w->getDictionaries() as $d) {
                    $d->addWord($w);
                    $this->get('persist')->persistAndFlush($t);
                }

                return $this->redirect($this->generateUrl('newWord', array('id' => $d->getConvertId())));
            }
            $params['dictionary'] = $d;
            $params['form'] = $form->createView();

            return $params;
        }
        return $this->redirect($this->generateUrl('default'));
    }

    /**
     * @Route("/{_locale}/s/{template}", name="static" )
     * @Template
     */
    public function staticAction($template)
    {
        return $this->render('MainDefaultBundle:Static:' . $template . '.html.twig', array());
    }

    public function clean()
    {
        /*
         SET FOREIGN_KEY_CHECKS=0;
TRUNCATE `DictionariesWord`;
TRUNCATE `Dictionary`;
TRUNCATE `DictionaryScore`;
TRUNCATE `fos_user`;
TRUNCATE `Point`;
TRUNCATE `Test`;
TRUNCATE `Translation`;
        */
    }

}
