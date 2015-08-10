<?php
namespace Main\DefaultBundle\Command;

use Main\DefaultBundle\Entity\Sense;
use Main\DefaultBundle\Entity\Suck;
use Main\DefaultBundle\Entity\Word;
use Main\DefaultBundle\Entity\Ww;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

class InsertCommand extends ContainerAwareCommand
{
    public $persistWords = array('en' => array(), 'fr' => array());
  
    protected function configure()
    {
        $this
            ->setName('oneShot:abstract');
    }

    protected function getWord($w, $local)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
ini_set('memory_limit', '-1');
        //$w = utf8_decode($w);
        $w = str_replace('<br>', '', $w);
        echo "\n" . '('.$local.') ';
        if($local == 'en') {

            $w = str_replace('<span title="something">[sth]</span>', '[sth]', $w);
            $w = str_replace('<span title="somebody">[sb]</span>', '[sb]', $w);
            $w = str_replace('<span title="somebody or something">[sb/sth]</span>', '[sb/sth]', $w);
        }
        $w = trim($w);

        if ($obj = $em->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => $w, 'local' => $local))) {
            echo 'Exist: ' . $obj->getWord();
            return $obj;
        } else if (array_key_exists($w, $this->persistWords[$local])) {
            echo 'PrExi: ' . $this->persistWords[$local][$w];
            return $obj;
          
        }

        echo 'NoExi: ' . $w;

        $obj = new Word();
        $obj->setLocal($local);
        $obj->setWord($w);
        $em->persist($obj);
        $this->persistWords[$local][$w] = $obj;
        //$em->flush();

        return $obj;
    }

}