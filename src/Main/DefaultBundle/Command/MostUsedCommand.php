<?php
namespace Main\DefaultBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

use Main\DefaultBundle\Entity\Sense;
use Main\DefaultBundle\Entity\Suck;
use Main\DefaultBundle\Entity\Word;
use Main\DefaultBundle\Entity\Ww;

class MostUsedCommand extends ContainerAwareCommand
{

    public $persistWords = array('en' => array(), 'fr' => array());
    public $type = array();

    protected function configure()
    {
        $this
            ->setName('oneShot:20k');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $handle = fopen($this->getContainer()->get('kernel')->getRootDir() . "/../dictSource/20k.txt", "r");
        $i = $l = 0;
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $l++;
                $line = trim($line);
                if (null == ($word = $em->getRepository('MainDefaultBundle:Word')->findBy(array('word' => $line)))) {
                    $i++;
                    echo $line. "\n";
                }
                if($i > 50) {exit;}
                
            }
    
            fclose($handle);
        }
        //http://mymemory.translated.net/api/get?q=where&langpair=en|fr
        echo $i . '/' . $l;
    }
}