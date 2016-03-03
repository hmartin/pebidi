<?php
namespace AppBundle\Command\once;

use AppBundle\Command\suck\InsertCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

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
            ->setName('oneShot:merge');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $this->clean();
        $result = $this->decode('/../doc/dictSource/arrayWr.json');
        $stopwatch = new Stopwatch();
        $stopwatch->start('eventName');

        echo 'merge: ' . count($result) . "\n";
        //$this->getContainer()->get('app.word_model')->setFlush(false);
        $i = 0;
        foreach ($result as $r) {
            $output->writeln($i++);
            $senses[] = $this->getContainer()->get('app.word_model')->postImprove($r);
        }
        $output->writeln('Let\'s flush');
        $em->flush();
        $event = $stopwatch->stop('eventName');
        $m = $event->getDuration() / 1000 / 60;
        $output->writeln('Let\'s flush' . $m);
    }

    private function decode($filepath)
    {
        $file = file_get_contents($this->getContainer()->get('kernel')->getRootDir() . $filepath);
        //echo $file;
        echo mb_detect_encoding ($file);
//$file = mb_convert_encoding($file, "UTF-8", "ASCII");
//$file = iconv('ASCII', 'UTF-8//IGNORE', $file);
//$file = mb_convert_encoding($file, 'HTML-ENTITIES');
        $file = utf8_decode ($file);
        //echo "\n"."\n"."\n".$file;
        //$file = iconv('ASCII', 'UTF-8', $file);
        echo mb_detect_encoding ($file);
        
        sleep(2);
        $a = json_decode($file, true);
        echo $filepath . ': ' . count($a) . "\n";
        
        return $a;
    }
}