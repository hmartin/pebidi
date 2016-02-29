<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sense;
use AppBundle\Entity\Ww;
use AppBundle\Entity\WordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\DomCrawler\Crawler;

use AppBundle\Entity\Word;

class WordController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(section="Word", description="Get word detail",
     *  parameters={
     *      {"name"="improve", "dataType"="bolean", "required"=true}
     *  }
     * )
     * @Rest\View()
     */
    public function getAction(Request $request, $w)
    {
        if (!is_int($w)) {
            $w = $this->getDoctrine()->getRepository('AppBundle:Word')->findOneByWord($w);
        }

        $wordRepo = $this->getDoctrine()->getRepository('AppBundle:Word');
        $results = $wordRepo->getWordTranslationConcat($w);


        return $results;
    }

    /**
     * @ApiDoc(section="Word", description="Post Word to Dic",
     *  requirements={
     *      { "name"="id", "dataType"="integer", "requirement"="\d+", "description"="dic id" },
     *      { "name"="w", "dataType"="string", "requirement"="\d+", "description"="Word" }
     *  },
     * )
     * @Rest\View()
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($d = $this->getDoctrine()->getRepository('AppBundle:Dictionary')->find($request->get('id'))) {

            $w = $this->getWord($request->get('w'), 'en');

            if (!$d->getWords()->contains($w)) {
                $d->addWord($w);
            }

            $em->flush();

            return array('dic' => $d->getJsonArray());
        }
        throw new \Exception('Something went wrong!');
    }

    private function getWord($word, $local)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$w = $this->getDoctrine()->getRepository('AppBundle:Word')->findOneBy(array('word' => $word, 'local' => $local))) {
            $w = new Word();
            $w->setWord($word);
            $w->setLocal($local);
            $em->persist($w);
            $em->flush();
        }

        return $w;
    }

    private function getWordType($word, $category, $expression)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$wt = $this->getDoctrine()->getRepository('AppBundle:WordType')->findOneBy(
            array('word' => $word, 'category' => $category, 'expression' => $expression))
        ) {
            $wt = new WordType();
            $wt->setWord($word);
            $wt->setCategory($category);
            $wt->setExpression($expression);
            $em->persist($wt);
            $em->flush();
        }

        return $wt;
    }

    /**
     * @ApiDoc(section="Word", description="Remove Word to Dic",
     *  requirements={
     *      { "name"="id", "dataType"="integer", "requirement"="\d+", "description"="dic id" },
     *      { "name"="w", "dataType"="string", "requirement"="\d+", "description"="word id" }
     *  },
     * )
     * @Rest\View()
     */
    public function postRemoveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($w = $this->getDoctrine()->getRepository('AppBundle:Word')->find($request->request->get('id')) and
            $d = $this->getDoctrine()->getRepository('AppBundle:Dictionary')->find($request->request->get('did'))
        ) {
            $d->getWords()->removeElement($w);
            $em->persist($d);
            $em->flush();

            return array('dic' => $d->getJsonArray());
        }
        throw new \Exception('postDeleteWordAction went wrong!');
    }

    /**
     * @ApiDoc(section="Word", description="Improve Word",
     *  requirements={
     *      { "name"="data", "dataType"="string", "requirement"="\d+", "description"="word id" }
     *  },
     * )
     * @Rest\View()
     */
    public function postImproveAction(Request $request)
    {
        return $this->postImprove($request->get('data'));
    }
        
    public function postImprove($data)
    {
        $em = $this->getDoctrine()->getManager();
        //echo '<pre>';
        /* foreach post line */
        foreach ($data as $sense) {

            $w = $sense['w'];

            $wordString = $w;
            $expression = null;
            $kExplode = explode(' ', $w);
            // Check if composed word
            if (count($kExplode) > 1) {
                $expression = $w;
                $wordString = $kExplode['0'];
            }
            $word = $this->getWord($wordString, 'en');
            $category = '';
            if (array_key_exists('category', $sense)) {
                $category = $sense['category'];
            }

            $wordType = $this->getWordType($word, $category, $expression);

            $oldWw = $this->getDoctrine()->getRepository('AppBundle:Ww')->findBy(
                array('word1' => $wordType));

            foreach ($oldWw as $ww) {
                $em->remove($ww);
            }
            $em->flush();

            $senseStr = null;
            if (array_key_exists('sense', $sense)) {
                $senseStr = $sense['sense'];
            }
            $senseEntity = new Sense();
            $senseEntity->setSense($senseStr);
            $senseEntity->setLocal('en');
            $em->persist($senseEntity);

            $translations = explode(',', str_replace(array(', ', ' ,'), ',', $sense['concat']));

            $i = 0;
            foreach ($translations as $t) {
                $wordString = trim($t);
                $expression = null;
                $kExplode = explode(' ', $w);
                if (count($kExplode) > 1) {
                    $expression = $w;
                    $wordString = $kExplode['0'];
                }
                $tradWord = $this->getWord($wordString, 'fr');
                $tradWordType = $this->getWordType($tradWord, '', $expression);
                $ww = $this->getDoctrine()->getRepository('AppBundle:Ww')->findOneBy(array('word1' => $wordType, 'word2' => $tradWordType));
                if (is_null($ww)) {
                    $ww = new Ww();
                    $ww->setWord1($wordType);
                    $ww->setWord2($tradWordType);
                    $ww->addSense($senseEntity);
                    $ww->setPriority($i++);
                    $em->persist($ww);
                }
            }
        }

        $em->flush();
        $results = $this->getDoctrine()->getRepository('AppBundle:Word')->getWordTranslationConcat($word);

        return $results;
    }
    
 
    /**
     * @ApiDoc(section="Word", description="Suck Word",
     *  requirements={
     *      { "name"="word", "dataType"="string", "requirement"="\d+", "description"="word to suck" }
     *  },
     * )
     * @Rest\View()
     */   
    public function suckOneFromWebAction($word)
    {
        $url = 'http://www.wordreference.com/enfr/'.$word;
        
        $curl_handle = curl_init();
        \curl_setopt($curl_handle, CURLOPT_URL, $url);
        \curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        \curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($curl_handle, CURLOPT_USERAGENT, 'googlebot');
        $subhtml = \curl_exec($curl_handle);
        \curl_close($curl_handle);
        $crawler = new Crawler($subhtml);
        //$output->writeln($l->getUri());
            $crawler = $crawler->filter('table.WRD > tr');
            
            $class = '';
            $newWord = true;
            
            $k = $i = 0;

            $arrayTrans = $senses = $global = array();
            foreach ($crawler as $domElement) {
                $sense = '';
                if ($domElement->getAttribute('class') == 'even' || $domElement->getAttribute('class') == 'odd') {
                    $tr = new Crawler($domElement);
                    if ($class != $domElement->getAttribute('class')) {
                        $k = $k + 0.1;
                        $priority = 0;
                        $class = $domElement->getAttribute('class');
                        if ($newWord) {
                            if ($newWord = $tr->filter('strong')->count() && null !== ($newWord = $tr->filter('strong')->eq(0)->html())) {
                                $t = '';
                                if (null !== ($type = $tr->filter('em')->eq(0))) {

                                    $type->filter('span')->each(function (Crawler $crawler) {
                                        foreach ($crawler as $node) {
                                            $node->parentNode->removeChild($node);
                                        }
                                    });
                                    $t = $type->html();

                                }
                                $newWord = explode(',', $newWord);
                                $w = $this->cleanString(utf8_decode($newWord['0']));

                            } else {
                                //echo 'error' . $s->getUrl() . '<br>';
                            }
                        }

                        if ((null !== ($senseValue = $tr->filter('td')->eq(1))) && count($senseValue) > 0) {

                            $senseValue->filter('span')->each(function (Crawler $crawler) {
                                foreach ($crawler as $node) {
                                    $node->parentNode->removeChild($node);
                                }
                            });
                            $senseValue->filter('i')->each(function (Crawler $crawler) {
                                foreach ($crawler as $node) {
                                    $node->parentNode->removeChild($node);
                                }
                            });
                            $sensesArrayValue = explode(',', $senseValue->html());
                            $sense = $this->cleanSense(utf8_decode($sensesArrayValue['0']));

                        }

                    if (null !== ($trans = $tr->filter('td.ToWrd')->eq(0))) {
                        if (null == $trans->filter('span[title*="translation unavailable"]')->eq(0)) {
                            continue;
                        }
                        
                        $t_trans = '';
                        if (null !== ($type_trans = $trans->filter('em')->eq(0))) {
                            $type_trans->filter('span')->each(function (Crawler $crawler) {
                                foreach ($crawler as $node) {
                                    $node->parentNode->removeChild($node);
                                }
                            });
                            $t_trans = $type->html();
                        }
                        
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
                            
                            $wsClean = [];
                            $ws = explode(',', $trans->html());
                            foreach ($ws as $each) {
                                $priority = $priority + 1;
                                $prior = $priority;

                                $tw = $this->cleanString(utf8_decode($each));
                                //echo 'c:' . $class . '   s:'.$sense.'   w:'. $w  .'   t:' . $tw . ' $prior:' . $prior."\n" ;
                                if ($tw) {
                                    $arrayTrans[$tw] = array('type' => $t_trans);
                                    $wsClean[] = $tw;
                                }

                            }
                            if (count($wsClean)) {
                            $senses[] = array('w' => $word, 'category' => $t, 'sense' => $sense, 'concat' => implode(',', $wsClean));
                                
                            }
                        }

                    }

                    }
                }
            }
            
        //dump(senses);    
        //return $senses;
        return $this->postImprove($senses);
    }
    
    protected function cleanSense($string)
    {
        $string = str_replace('(', '', $string);
        $string = str_replace(')', '', $string);

        if (!preg_match('/^[\p{L}-\s\-\']*$/u', $string)) {
            //echo "\n". 'wrong sense' .$string;
            return null;
        }

        return trim($string);
    }
    protected function cleanString($string)
    {
        if (mb_detect_encoding($string) != 'UTF-8') {
            $string = iconv('ASCII', 'UTF-8', $string);
        }

        if (substr_count($string, ' ') > 1 or $this->starts_with_upper($string) ) {
            return null;
        }
        $endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
        $string = str_replace('*', '', $string);
        $string = str_replace('...', '', $string);
        $string = str_replace('‐', '-', $string);
        $string = str_replace('<br>', '', $string);
        $string = str_replace('<span title="something">[sth]</span>', '[sth]', $string);
        $string = str_replace('<span title="somebody">[sb]</span>', '[sb]', $string);
        $string = str_replace('<span title="somebody or something">[sb/sth]</span>', '[sb/sth]', $string);

        if (!preg_match('/^[-\'\p{L}\p{M}\s-]+$/u', $string)) {
            //echo "\n". 'not accepted: '. $string;
            return null;
        }

        return trim($string);
    }

    private function starts_with_upper($str) 
    {
        $chr = mb_substr ($str, 0, 1, "UTF-8");
        return mb_strtolower($chr, "UTF-8") != $chr;
    }
}