<?php
namespace Main\DefaultBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class OneShotCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('oneShot:wordDefEn')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine');
        $ts = $em->getRepository('MainDefaultBundle:DefinitionEn')->findAll();

        $output->writeln('start');
        foreach($ts as $t) {
            if ($w = $em->getRepository('MainDefaultBundle:Word')->find(array('word' => $t->getLemma()))) {
                $t->setWord($w);

                $output->writeln($t->getId());
                $em->persist($w);
            }
        }
        $this->em->flush();
        //update DefinitionEn d set d.word_id = (select w.id from Word w where w.word LIKE d.lemma);
    }
}