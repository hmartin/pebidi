<?php
namespace AppBundle\Command\Cron;

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
                ->leftJoin('w.subWords', 's',
                    \Doctrine\ORM\Query\Expr\Join::WITH, 's.expression IS NULL')
                ->where('s.id IS NULL')
                ->andWhere('w.disabled = 0')
                ->andWhere('w.local = \'en\'')
                ;
        $results = $qb->getQuery()->getResult();
        $output->writeLn(date("Y-m-d h:i:sa") . ' Start with: ' .count($results). ' words');
        
        foreach($results as $r) {
            $output->writeLn($r->getWord());
            
            if ($senses = $this->getContainer()->get('app.suck_model')->wordToArray($r->getWord())) {
                $this->getContainer()->get('app.word_model')->postImprove($r->getWord(), $senses);
            } else {
                $r->setDisabled(1);
                $output->writeLn('disabled!');
            }
        }
        $em->flush();
    }
}
