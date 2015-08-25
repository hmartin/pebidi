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
        
        $global = array();
        $next = true;
        $output->writeln('count $entries:'. count($entries));
        foreach ($entries as $second_gen) {
            
            $fromString = $second_gen->k->__toString();
            $type_w = $this->getType($fromString);
            if ($fromString = $this->cleanString($fromString)) {
                $stringsTrans = explode(',', $second_gen->__toString());
                
                $arrayTrans = $senses = array();
                foreach($stringsTrans as $stringTrans) {
                    $type_t = $this->getType($stringTrans);
                    if ($stringTrans = $this->cleanString($stringTrans)) {
                        $arrayTrans[] = array('w' => $stringTrans, 'type' => $type_t);
                    }
                }
                if (count($arrayTrans) > 0) {
                    
                    $senses[] = array('s' => '', 't' => $arrayTrans );
                    $g =  array('w' => $fromString, 'type' => $type_w, 'senses' => $senses);
                    $global[] = $g;
                }
            }
            $i++;
        }
       
        $output->writeln('count array:'. count($global));
        $file = fopen($this->getContainer()->get('kernel')->getRootDir() . '/../dictSource/xdxf/eng-fra.json', "w");
        $output->writeln(fwrite($file, json_encode($global)));
        fclose($file);
    }

    private function getType(&$string)
    {
        $type = null;
        if (preg_match('#\((.*?)\)#', $string, $match)) {

            $type = $match[1];
            $string = preg_replace('#\((.*?)\)#', '', $string);

        }
        $string = trim($string);
        if ($type) {
            if (!isset($this->type[$type])) {
                $this->type[$type] = 0;
            }
            $this->type[$type] = 1 + $this->type[$type];
        }

        return $type;
    }
}