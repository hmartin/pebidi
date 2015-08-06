<?php
namespace Main\DefaultBundle\Command;

use Main\DefaultBundle\Entity\Suck;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class FreedictCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('oneShot:freedict');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        //$p = $em->getRepository('MainDefaultBundle:Suck')->findAll();
        $xml = simplexml_load_file($this->getContainer()->get('kernel')->getRootDir().'/../src/Main/DefaultBundle/freeDict/fra-eng.xml');
      $entries = $xml->text->body->entry;
      echo $entries->count();
      foreach ($entries as $second_gen) {
        //echo "\n".'---1---'.$second_gen->form->orth;
      }
        $em->flush();

    }
}