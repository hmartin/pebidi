<?php
namespace Main\DefaultBundle\Command\suck;

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

class FreedictCommand extends InsertCommand
{
    protected function configure()
    {
        $this
            ->setName('oneShot:freedict');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $xml = simplexml_load_file($this->getContainer()->get('kernel')->getRootDir() . '/../dictSource/eng-fra/eng-fra.tei');
        $entries = $xml->text->body->entry;
        $global = array();
        foreach ($entries as $second_gen) {

            if ($word = $this->cleanString($second_gen->form->orth)) {
                $arrayTrans = array();
                foreach ($second_gen->sense as $senses) {
                    foreach ($senses->cit as $cits) {
                        $tw = $this->cleanString($cits->quote);
                        $arrayTrans[$tw] = array();

                    }
                }

                if ($word && count($arrayTrans) > 0) {
                    $senses = array('s' => '', 't' => $arrayTrans);
                    $global[$word] = array('senses' => $senses);
                }
            }
        }

        $output->writeln('count array:' . count($global));
        $file = fopen($this->getContainer()->get('kernel')->getRootDir() . '/../dictSource/eng-fra/eng-fra.json', "w");
        $output->writeln(fwrite($file, json_encode($global)));
        fclose($file);
    }
}