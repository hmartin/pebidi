<?php
namespace Main\DefaultBundle\Command;

use Main\DefaultBundle\Entity\Suck;
use Main\DefaultBundle\Entity\Sense;
use Main\DefaultBundle\Entity\Word;
use Main\DefaultBundle\Entity\Ww;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class XddictCommand extends InsertCommand
{
    protected function configure()
    {
        $this
            ->setName('oneShot:xddict');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $xml = simplexml_load_file($this->getContainer()->get('kernel')->getRootDir() . '/../dictSource/xdxf/eng-fra.xml');
        $entries = $xml->ar;

        $nbExist = $k = $i = 0;
        $local = 'en';
        $next = true;
        foreach ($entries as $second_gen) {
            $string = $second_gen->k;
            $this->getType($string);
            if ($string == 'can') {
                $word = $this->getWord($string, $local);
            $string = $second_gen;
            $this->getType($string);
                $word = $this->getWord($string, 'fr');
                
            }
        }
        
        var_dump($this->type);
        $em->flush();

    }
    
    private function getType(&$string) {
        $type = null;
            if( preg_match( '#\((.*?)\)#', $string, $match ) ) {
                
                $type = $match[1];
                $string = preg_replace('#\((.*?)\)#', '', $string);
                
            }
            $string = trim($string);
        if ($type) {
            $this->type[$type] = count($this->type[$type]);
        } 
            
        return $type;
    }
}