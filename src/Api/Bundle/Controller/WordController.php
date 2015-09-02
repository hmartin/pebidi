<?php

namespace Api\Bundle\Controller;

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
     * @ApiDoc(section="Word", description="Get json info dictionary with id",
     *  parameters={
     *      {"name"="improve", "dataType"="bolean", "required"=true}
     *  }
     * )
     * @Rest\View()
     */
    public function getAction(Request $request, Word $w)
    {
        $wordRepo = $this->getDoctrine()->getRepository('MainDefaultBundle:Word');
       if ($request->query->get('improve')) {
          $results = $wordRepo->getWordFullTranslation($w);
       } else {
          $results = $wordRepo->getWordTranslationConcat($w);
       }
      
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

        if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find($request->request->get('id'))) {
            $word = $request->request->get('w');

            if (!$w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => $word))) {
                $w = new Word();
                $w->setWord($word);
                $w->setLocal('en');
                $em->persist($w);
                $em->flush();
            }
            if (!$d->getWords()->contains($w)) {
                $d->addWord($w);
            }
          
            $em->flush();

            return array('dic' => $d->getJsonArray());
        }
        throw new \Exception('Something went wrong!');
    }

    /**
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
}