<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DictionaryWord;
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
     * @ApiDoc(section="Word", description="Post Word to Dic return [msg => valid|notExistYet|error, dic => jsonDic]",
     *  requirements={
     *      { "name"="id", "dataType"="integer", "requirement"="\d+", "description"="dic id" },
     *      { "name"="w", "dataType"="string", "requirement"="\d+", "description"="Word" }
     *  }
     * )
     * @Rest\View()
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if (($d = $this->getDoctrine()->getRepository('AppBundle:Dictionary')->find($request->get('id')))
                && ($word = $request->get('w'))) {
            $edit = false;
            if ($this->getUser() && $this->getUser()->hasRole('ROLE_USER')) {
                $edit = true;
            }
            
            $msg = 'valid';
            
            // TODO: Check if expression
            if (false == ($w = $em->getRepository('AppBundle:Word')->findOneBy(array('word' => $word, 'local' => 'en')))) 
            {
                $w = $this->get('app.word_model')->getWord($word, 'en', true);
                $msg = 'notExistYet';    
            }

            if (!$this->get('app.dictionary_word_model')->addWord($d, $w)) {
                $msg = 'alreadyIn';
            }

            $em->flush();

            return ['msg' => $msg, 'dic' => $this->get('app.dictionary_model')->createJson($d)];
        }
        
        return array('msg' => 'error');
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
        if ($w = $this->getDoctrine()->getRepository('AppBundle:Word')->find($request->request->get('id')) &&
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
     *      { "name"="word", "dataType"="string", "requirement"="\d+", "description"="Word" },
     *      { "name"="data", "dataType"="string", "requirement"="\d+", "description"="Array of senses" }
     *  },
     * )
     * @Rest\View()
     */
    public function postImproveAction(Request $request)
    {
        $this->get('app.word_model')->setDelete(true);

        return $this->get('app.word_model')->postImprove($request->get('word'), $request->get('data'));
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
        $senses = $this->get('app.suck_model')->wordToArray($word);
        
        return $this->get('app.word_model')->postImprove($word, $senses);
    }
}
