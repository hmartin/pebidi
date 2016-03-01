<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\Ww;
use AppBundle\Entity\SubWord;

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

            $edit = false;
            if ($this->getUser()->hasRole('ROLE_USER')) {
                $edit = true;
            }
            // TODO: Check if expression
            if ($w = $this->getWord($request->get('w'), 'en', $edit)) 
            {
                if (!$d->getWords()->contains($w)) {
                    $d->addWord($w);
                }
    
                $em->flush();
            }

            return array('dic' => $d->getJsonArray());
        }
        throw new \Exception('Something went wrong!');
    }

    private function getWord($word, $local, $createIfNotExist = false)
    {
        $em = $this->getDoctrine()->getManager();
        $w = null;
        if (!$w = $this->getDoctrine()->getRepository('AppBundle:Word')->findOneBy(array('word' => $word, 'local' => $local))) {
            if ($createIfNotExist) {
                $w = new Word();
                $w->setWord($word);
                $w->setLocal($local);
                $em->persist($w);
                $em->flush();
                
                // auto improve
                if (true) {
                    $this->suckOneFromWebAction($word);
                }
            }
        }

        return $w;
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
        $data = array_values($data);
        $word = null;
        
        /* foreach post line */
        foreach ($data as $sense) {

            $w = $sense['w'];

            $wordString = $w;
            $expression = null;
            //$kExplode = explode(' ', $w);
            $kExplode =preg_split( "/( |-)/", $w);
            // Check if composed word
            if (count($kExplode) > 1) {
                $expression = $w;
                $wordString = $kExplode['0'];
            }
            
            if(!$word) {
                $word = $this->getWord($wordString, 'en', true);
            }
            
            foreach($word->getSubWords() as $sw)
            {
                $oldWw = $this->getDoctrine()->getRepository('AppBundle:Ww')->findBy(
                    array('word1' => $sw));

                foreach ($oldWw as $ww) {
                    $em->remove($ww);
                }
                $em->remove($sw);
                $em->flush();
            }

            $category = '';
            if (array_key_exists('category', $sense)) {
                $category = $sense['category'];
            }

            $senseStr = '';
            if (array_key_exists('sense', $sense)) {
                $senseStr = $sense['sense'];
            }
            if (!array_key_exists('additional', $sense)) {
                $sense['additional'] = 0;
            }

            $subWord = $this->getSubWord($word, $category, $expression, $senseStr);

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
                $tradWord = $this->getWord($wordString, 'fr', true);
                $tradSubWord = $this->getSubWord($tradWord, '', $expression, '');
                $ww = $this->getDoctrine()->getRepository('AppBundle:Ww')->findOneBy(array('word1' => $subWord, 'word2' => $tradSubWord));
                if (is_null($ww)) {
                    $ww = new Ww();
                    $ww->setWord1($subWord);
                    $ww->setWord2($tradSubWord);
                    $ww->setAdditional($sense['additional']);
                    $ww->setPriority($i++);
                    $em->persist($ww);
                }
            }
        }

        $em->flush();
        $results = $this->getDoctrine()->getRepository('AppBundle:Word')->getWordTranslationConcat($word);

        return $results;
    }

    private function getSubWord($word, $category, $expression, $sense)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$wt = $this->getDoctrine()->getRepository('AppBundle:SubWord')->findOneBy(
            array('word' => $word, 'category' => $category, 'expression' => $expression, 'sense' => $sense))
        ) {
            $wt = new SubWord();
            $wt->setWord($word);
            $wt->setCategory($category);
            $wt->setExpression($expression);
            $wt->setSense($sense);
            $em->persist($wt);
            $em->flush();
        }

        return $wt;
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
        $url = 'http://www.wordreference.com/enfr/' . $word;

        $curl_handle = curl_init();
        \curl_setopt($curl_handle, CURLOPT_URL, $url);
        \curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        \curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($curl_handle, CURLOPT_USERAGENT, 'googlebot');
        $subhtml = \curl_exec($curl_handle);
        \curl_close($curl_handle);
        
        $senses = $this->get('app.wr_suck')->htmlToArray($subhtml);
        
        return $this->postImprove($senses);
    }
}