<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

use AppBundle\Entity\Sense;
use AppBundle\Entity\Suck;
use AppBundle\Entity\Word;
use AppBundle\Entity\WordType;
use AppBundle\Entity\Ww;

class SuckOneCommand extends ContainerAwareCommand
{

    public $persistWords = array('en' => array(), 'fr' => array());
    public $type = array();

    protected function configure()
    {
        $this
            ->setName('pebidi:suckOne')
            ->addOption('word', null, InputOption::VALUE_REQUIRED, '[--word=word]');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $word = $input->getOption('word');
            
        if ($senses = $this->getContainer()->get('app.suck_model')->wordToArray($word)) {
            
            $this->getContainer()->get('app.word_model')->postImprove($word, $senses);    
        }
        
    }
}
