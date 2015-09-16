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
use Main\DefaultBundle\Entity\WordType;
use Main\DefaultBundle\Entity\Ww;

class SuckOneCommand extends ContainerAwareCommand
{

    public $persistWords = array('en' => array(), 'fr' => array());
    public $type = array();

    protected function configure()
    {
        $this
            ->setName('oneShot:suckOne')
            ->addArgument('origin', InputOption::VALUE_REQUIRED, '[--origin=origin]')
            ->addArgument('to', InputOption::VALUE_REQUIRED, '[--to=to]')
            ->addOption('word', null, InputOption::VALUE_REQUIRED, '[--word=word]');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $origin = $input->getArgument('origin');
        $to = $input->getArgument('to');
        $w = $input->getOption('word');
        
        if ($obj = $em->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => $w, 'local' => $origin))) {
            echo 'Exist: ' . $obj->getWord();
        } else  {
            $json = file_get_contents('http://mymemory.translated.net/api/get?q='.$w.'&langpair='.$origin.'|'.$to.'');
    
            $data = json_decode($json,true);
            
            $t = strtolower(trim($data['responseData']['translatedText']));
            
            $output->writeLn($t);
            if ($t != $w && preg_match('/^[-\'\p{L}\p{M}\s-]+$/u', $t)) {
            
                $wordOrigin = new Word();
                $wordOrigin->setLocal($origin);
                $wordOrigin->setWord($w);
                $em->persist($wordOrigin);
                
                $wordOriginType = new WordType();
                $wordOriginType->setWord($wordOrigin);
                $em->persist($wordOriginType);
            
                $wordTo = new Word();
                $wordTo->setLocal($to);
                $wordTo->setWord($w);
                $em->persist($wordTo);
                
                $wordToType = new WordType();
                $wordToType->setWord($wordTo);
                $em->persist($wordToType);
                
                $ww = new Ww();
                $ww->setWord1($wordOriginType);
                $ww->setWord2($wordToType);
                $ww->setPriority(0);
                $em->persist($ww);
                
                $em->flush();
                $output->writeLn('insert!');
            }
        }
    }
}
