<?php

namespace Api\Bundle\Controller;

use Main\DefaultBundle\Entity\Sense;
use Main\DefaultBundle\Entity\Ww;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Main\DefaultBundle\Entity\Word;

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
            $w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->findOneByWord($w);
        }

        $wordRepo = $this->getDoctrine()->getRepository('MainDefaultBundle:Word');
        $results = $wordRepo->getWordTranslationConcat($w);


        return $results;
    }

    /**
     * @ApiDoc(section="Word", description="Post Word to Dic",
     *  requirements={
     *      { "name"="id", "dataType"="integer", "requirement"="\d+", "description"="dic id" },
     *      { "name"="w", "dataType"="string", "requirement"="\d+", "description"="word id" }
     *  },
     * )
     * @Rest\View()
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->get('id'))) {

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
        if (!$w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => $word, 'local' => $local))) {
            $w = new Word();
            $w->setWord($word);
            $w->setLocal($local);
            $em->persist($w);
            $em->flush();
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
        if ($w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->find($request->request->get('id')) and
            $d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->request->get('did'))
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
        $em = $this->getDoctrine()->getManager();
        $data = $request->get('data');
        foreach ($data as $sense) {

            $w = $sense['w'];

            $wordString = $w;
            $expression = null;
            $kExplode = explode(' ', $w);
            if (count($kExplode) > 1) {
                $expression = $w;
                $wordString = $kExplode['0'];
            }
            $word = $this->getWord($wordString, 'en');
            dump($word->getId());
            $category = '';
            if (array_key_exists('category', $sense)) {
                $category = $sense['category'];
            }

            $wordType = $this->getDoctrine()->getRepository('MainDefaultBundle:WordType')->findOneBy(
                array('word' => $word, 'category' => $category, 'expression' => $expression));

            dump($category);
            dump($expression);
            dump($wordType);
            $oldWw = $this->getDoctrine()->getRepository('MainDefaultBundle:Ww')->findBy(
                array('word1' => $wordType));
//dump($oldWw);
            foreach ($oldWw as $ww) {
                $em->remove($ww);
            }
            $em->flush();

            $senseStr = null;
            if (array_key_exists('sense', $sense)) {
                $senseStr = $sense['sense'];
            }

            $trads = explode(',', str_replace(array(', ', ' ,'), ',', $sense['concat']));
            $i = 0;
            foreach ($trads as $t) {
                $wordString = trim($t);
                $expression = null;
                $kExplode = explode(' ', $w);
                if (count($kExplode) > 1) {
                    $expression = $w;
                    $wordString = $kExplode['0'];
                }
                $tradWord = $this->getWord($wordString, 'fr');
                dump($tradWord);
                $tradWordType = $this->getDoctrine()->getRepository('MainDefaultBundle:WordType')->findOneBy(
                    array('word' => $tradWord, 'expression' => $expression));
dump($tradWordType);
                $ww = $this->getDoctrine()->getRepository('MainDefaultBundle:Ww')->findOneBy(array('word1' => $wordType, 'word2' => $tradWordType));
                dump($ww);
                if (is_null($ww)) {
                    $sense = new Sense();
                    $sense->setSense($senseStr);
                    $sense->setLocal('en');

                    $em->persist($sense);

                    $ww = new Ww();
                    $ww->setWord1($wordType);
                    $ww->setWord2($tradWordType);
                    $ww->addSense($sense);
                    $ww->setPriority($i++);
dump($ww);
                    $em->persist($ww);

                }
            }
        }

        $em->flush();
        $results = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->getWordTranslationConcat($word);

        return $results;
    }
}