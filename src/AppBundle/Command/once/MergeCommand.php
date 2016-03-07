<?php
namespace AppBundle\Command\once;

use AppBundle\Command\suck\InsertCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

use AppBundle\Entity\Sense;
use AppBundle\Entity\Category;
use Symfony\Component\Stopwatch\Stopwatch;

class MergeCommand extends InsertCommand
{

    public $persistWords = array('en' => array(), 'fr' => array());
    public $persistWordsType = array('en' => array(), 'fr' => array());
    public $persistCategorys = array();
    public $type = array();

    protected function configure()
    {
        $this
            ->setName('oneShot:merge')
            ->addOption('start', 'i', InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');
        $page = $input->getOption('start');
        $em = $this->getContainer()->get('doctrine')->getManager();
        //$this->clean();
        
        $file = file_get_contents($this->getContainer()->get('kernel')->getRootDir() . '/../doc/dictSource/arrayWr.json');
        $file = utf8_decode ($file);
        $all =  array_chunk (json_decode($file, true), 30);
        
        $result = $all[$page];
        echo 'arrayWs : ' . count($result) . "\n";
        $this->getContainer()->get('app.word_model')->setDelete(false);
        $i = 0;
        foreach ($result as $r) {
            if ($i>30) {break;}
            $output->writeln($i++);
            $senses[] = $this->getContainer()->get('app.word_model')->postImprove($r);
        }
        $em->flush();
        $next = $page +1;
        $command = 'php '. $this->getContainer()->get('kernel')->getRootDir() .'/console oneShot:merge --start ' .$next ." > /dev/null 2>/dev/null &";
        
        $output->writeln($command);
        //$p = new Process($command);
        //$p->start();
        exec($command);
        
    }
}