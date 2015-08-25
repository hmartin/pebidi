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

abstract class InsertCommand extends ContainerAwareCommand
{
    public $persistWords = array('en' => array(), 'fr' => array());
    public $type = array();

    protected function getWord($w, $local)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        ini_set('memory_limit', '-1');

        if ($obj = $em->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => $w, 'local' => $local))) {
            echo 'Exist: ' . $obj->getWord();
            return $obj;
        } else if (array_key_exists($w, $this->persistWords[$local])) {
            echo 'PrExi: ' . $this->persistWords[$local][$w];
            return $this->persistWords[$local][$w];

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
    
    

    protected function cleanString($string)
    {
        if (substr_count($string, ' ') > 2 or $this->starts_with_upper($string) ) {
            return null;
        }
        if (substr_count($string, '.') > 0 or substr_count($string, '[') > 0 || substr_count($string, '(') > 0 ) {
            echo $string;
            return null;
        }
        
        $w = $string;
        //$w = utf8_decode($w);
        $w = str_replace('<br>', '', $w);
        $w = str_replace('<span title="something">[sth]</span>', '[sth]', $w);
        $w = str_replace('<span title="somebody">[sb]</span>', '[sb]', $w);
        $w = str_replace('<span title="somebody or something">[sb/sth]</span>', '[sb/sth]', $w);
        
        $w = trim($w);

        return $w;
    }

    private function starts_with_upper($str) 
    {
        $chr = mb_substr ($str, 0, 1, "UTF-8");
        return mb_strtolower($chr, "UTF-8") != $chr;
    }
    
    protected function clean() 
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("
         SET FOREIGN_KEY_CHECKS=0;
        TRUNCATE `DictionariesWord`;
        TRUNCATE `Dictionary`;
        TRUNCATE `DictionaryScore`;
        TRUNCATE `Point`;
        TRUNCATE `Result`;
        TRUNCATE `Sense`;
        TRUNCATE `Test`;
        TRUNCATE `TestWord`;
        TRUNCATE `User`;
        TRUNCATE `Word`;
        TRUNCATE `Ww`;
        TRUNCATE `WwSenses`;
         SET FOREIGN_KEY_CHECKS=1;");
        $statement->execute();
        $statement->closeCursor();
    }
    
}