<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class UpdateNewWordCommand extends ContainerAwareCommand
{

    public $persistWords = array('en' => array(), 'fr' => array());
    public $type = array();

    protected function configure()
    {
        $this
            ->setName('pebidi:updateNewWord');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $qb = $em->getRepository('AppBundle:Word')->createQueryBuilder('w')
                ->leftJoin('w.subWords', 's')
                ->where('s.id IS NULL');
                
        $results = $qb->getQuery()->getResult();
        
        foreach($results as $r) {
            $output->writeLn($r->getWord());
            
            $html = $this->getContainer()->get('app.suck_model')->suckWithWr($r->getWord());
            
            $senses = $this->getContainer()->get('app.suck_model')->htmlToArray($html);
        
            $this->getContainer()->get('app.word_model')->postImprove($senses);
        }
    }
}
