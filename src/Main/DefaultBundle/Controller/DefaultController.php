<?php

namespace Main\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Form as f;
use Symfony\Component\DomCrawler\Crawler;

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

        //$w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->find(1);
        //$t = $this->getDoctrine()->getRepository('MainDefaultBundle:test')->find(1);
        //$test = $this->getDoctrine()->getRepository('MainDefaultBundle:test');
        //$p = $this->getDoctrine()->getRepository('MainDefaultBundle:point')->find(1);
        //$a = array('uid' => $u->getId(), 'did' => $d->getId());
        //$test->getAvgScore($a);

        //$results = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordsForTest(5, $d, $u);
        /*        $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->createQueryBuilder('w')
                    ->leftJoin('w.dictionaries','d')
                    ->leftJoin('w.groupsWords','gw')
                    ->where('d.id  = :did')
                    ->andWhere('gw.id  = :gwid')
                    ->setParameter('gwid', 1)
                    ->setParameter('did', 1)
                ;

                $results = $qb->getQuery()->getResult();
                $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->createQueryBuilder('d')
                    ->leftJoin('d.words', 'w')
                    ->leftJoin('d.translations', 't', 'WITH', 't.word = w.id')
                    ->where('d.id = :id')
                    ->setParameter(':id', 1);*/

/*        $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->createQueryBuilder('d');

        $qb
            ->select('w.id, w.word, t.translation')
            ->addSelect('SUM(p.point)/COUNT(p.id) AS global')
            ->addSelect('GROUP_CONCAT(DISTINCT def.definition) AS definitions')
            ->innerJoin('d.words', 'w')
            ->leftJoin('w.translations', 't')
            ->leftJoin('w.definitions', 'def')
            ->leftJoin('w.points', 'p')
            ->where('d.id = :id');
            $qb
                ->addSelect('SUM(IF(test.id IS NULL, p.point, 0))/SUM(IF(test.id IS NULL, 1, 0)) AS stat_sum_realised')
                ->leftJoin('p.test', 'test', 'WITH', 'test.user = :uid')
                ->setParameter(':uid', 1);


        $qb
            ->setParameter(':id', 1)
            ->groupBy('w.word');

SET FOREIGN_KEY_CHECKS=0;
TRUNCATE Word;
TRUNCATE WordWord;
SET FOREIGN_KEY_CHECKS=1;

*/

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
/*        $results = $qb->getQuery()->getResult();
        foreach ($results as $r) {
            var_dump($r);
        }*/
        return array();
    }

    /**
     * @Route("/{_locale}/test", name="test", requirements={"_locale" = "en|fr|de"} )
     * @Template
     */
    public function testAction(Request $request)
    {
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
