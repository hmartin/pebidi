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
        $local = 'en';
        $next = true;
        foreach ($entries as $second_gen) {

            $word = $this->getWord($second_gen->k, $local);
            $word = $this->getWord($second_gen, 'fr');
        }
        echo $nbExist . ' / ' . $entries->count() . "\n";
        $em->flush();

    }
}