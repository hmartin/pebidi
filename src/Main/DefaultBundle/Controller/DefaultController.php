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
use Main\DefaultBundle\Entity\Test;

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
        $em = $this->get('doctrine')->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("
         SET FOREIGN_KEY_CHECKS=0;
        TRUNCATE `DictionariesWord`;
        TRUNCATE `Dictionary`;
        TRUNCATE `DictionaryScore`;
        TRUNCATE `Point`;
        TRUNCATE `Result`;
        TRUNCATE `Test`;
        TRUNCATE `TestWord`;
        TRUNCATE `User`;
        TRUNCATE `Word`;
        TRUNCATE `SubWord`;
        TRUNCATE `Ww`;
         SET FOREIGN_KEY_CHECKS=1;");
        $statement->execute();
        $statement->closeCursor();
        $m = function () {
            list($usec, $sec) = explode(" ", microtime());
            return (float)$sec;
        };
        
        $file = file_get_contents($this->get('kernel')->getRootDir() . '/../doc/dictSource/arrayWr.json');
        $file = utf8_decode ($file);
        $result = json_decode($file, true);
        echo 'arrayWs : ' . count($result) . "\n";

        //$this->getContainer()->get('app.word_model')->setFlush(false);
        $i = $sum = 0;
        foreach ($result as $r) {
            if ($i>100) {break;}
            echo $i++;
            $s = $m();
            $senses[] = $this->get('app.word_model')->postImprove($r);
            $sum = $sum + $m() - $s;
        }
        echo '<br>Moy:'. ($sum/$i);
        return $this->render('MainDefaultBundle:Default:index.html.twig', array());
    }

}
