<?php

namespace AppBundle\Model;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\Ww;
use AppBundle\Entity\SubWord;

use AppBundle\Entity\Word;

class WordModel
{
    protected $flush = true;
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setFlush($flush)
    {
        $this->flush = $flush;
    }

    public function postImprove($data)
    {
        $data = array_values($data);
        $word = null;

        /* foreach post line */
        foreach ($data as $sense) {
            $w = $sense['w'];
            $wordString = $w;
            $expression = null;
            //$kExplode = explode(' ', $w);
            $kExplode = preg_split("/( |-)/", $w);
            // Check if composed word
            if (count($kExplode) > 1) {
                $expression = $w;
                $wordString = $kExplode['0'];
            }

            if (!$word) {
                $word = $this->getWord($wordString, 'en', true);
            }

            foreach ($word->getSubWords() as $sw) {
                $oldWw = $this->em->getRepository('AppBundle:Ww')->findBy(
                    array('word1' => $sw));
                foreach ($oldWw as $ww) {
                    $this->em->remove($ww);
                }
                $this->em->remove($sw);
                $this->flush();
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

            $translations = explode(',', $sense['concat']);

            $i = 0;
            foreach ($translations as $t) {
                $wordString = trim($t);
                $expression = null;
                $kExplode = explode(' ', $wordString);
                if (count($kExplode) > 1) {
                    $expression = $wordString;
                    $wordString = $kExplode['0'];
                }
                //dump($expression);
                $tradWord = $this->getWord($wordString, 'fr', true);
                $tradSubWord = $this->getSubWord($tradWord, '', $expression, '');

                //$ww = $this->em->getRepository('AppBundle:Ww')->findOneBy(array('word1' => $subWord, 'word2' => $tradSubWord));
                if (true) {
                    $ww = new Ww();
                    $ww->setWord1($subWord);
                    $ww->setWord2($tradSubWord);
                    $ww->setAdditional($sense['additional']);
                    $ww->setPriority($i++);
                    $this->em->persist($ww);
                }
            }
        }

        $this->flush();
        if ($this->flush) {
            $results = $this->em->getRepository('AppBundle:Word')->getWordTranslationConcat($word);

            return $results;
        }
    }

    public function getWord($word, $local, $createIfNotExist = false)
    {
        $w = null;
        if (!$w = $this->em->getRepository('AppBundle:Word')->findOneBy(array('word' => $word, 'local' => $local))) {
            if ($createIfNotExist) {
                $w = new Word();
                $w->setWord($word);
                $w->setLocal($local);
                $this->em->persist($w);
                $this->flush();
            }
        }
        return $w;
    }

    protected function flush()
    {
        if ($this->flush) {
            $this->em->flush();
        }
    }

    private function getSubWord($word, $category, $expression, $sense)
    {
        if (!$word->getId() || (!$wt = $this->em->getRepository('AppBundle:SubWord')->findOneBy(
                array('word' => $word, 'category' => $category, 'expression' => $expression, 'sense' => $sense)))
        ) {
            $wt = new SubWord();
            $wt->setWord($word);
            $wt->setCategory($category);
            $wt->setExpression($expression);
            $wt->setSense($sense);
            $this->em->persist($wt);
            $this->flush();
        }

        return $wt;
    }

}