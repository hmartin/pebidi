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
            if (!$w = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => $request->request->get('word')))) {
                $w = new e\Word();
                $w->setWord( $request->request->get('word') );
                $w->setLang('en');
                $this->get('persist')->persistAndFlush($w);
            }
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
    public function postWordsAction(Request $request)
    {
        if ($d = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->find( base_convert($request->request->get('id'), 23, 10) ))
        {
            $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Dictionary')->createQueryBuilder('d')
                ->leftJoin('d.translations', 't')
                ->leftJoin('t.word', 'w')
                ->select('w.word, t.translation')
                ->where('d.id = :id')
                ->setParameter(':id', $d->getId());

            $results = $qb->getQuery()->getResult();

            return array('words' => $results);
        }
        throw new \Exception('Something went wrong!');
    }


    /**
     * @Rest\View()
     */
    public function getAutoCompleteWordsAction(Request $request)
    {
        $qb = $this->getDoctrine()->getRepository('MainDefaultBundle:Word')->createQueryBuilder('wen')
            ->where('wen.word LIKE :word')
            ->setMaxResults(12)
            ->setParameter(':word', $request->query->get('word').'%');

        $results = $qb->getQuery()->getResult();

        return array('words' => $results);
    }
}