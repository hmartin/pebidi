<?php


namespace Main\DefaultBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Main\DefaultBundle\Entity as e;
use Main\DefaultBundle\Form as f;

class ApiWordController extends FOSRestController
{
    /**
     * @Rest\View()
     */
    public function postNewWordAction(Request $request)
    {

        if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find( base_convert($request->request->get('id'), 23, 10) ))
        {
            $w = new e\Word();
            $w->setWord( $request->request->get('word') );
            $this->get('persist')->persistAndFlush($w);
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
    public function getWordsAction(Request $request)
    {
        if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find( base_convert($request->request->get('id'), 23, 10) ))
        {
            $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->createQueryBuilder('d')
                ->leftJoin('d.translation', 't')
                ->leftJoin('t.word', 't')
                ->select('w.word, t.translation')
                ->where('d.id = ?')
                ->setParameter('id', $d->getId());
            $query = $qb->getQuery();
            $results = $query->getResults();
            return array('words' => $results);
        }
        throw new \Exception('Something went wrong!');
    }    
}