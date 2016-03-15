<?php
namespace AppBundle\Command\once\suck;

use AppBundle\Entity\Sense;
use AppBundle\Entity\Suck;
use AppBundle\Entity\Word;
use AppBundle\Entity\Ww;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;


class OneShot2Command extends InsertCommand
{
    protected function configure()
    {
        $this
            ->setName('oneShot:analyzeSuck');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $time_start = microtime_float();
        $this->clean();
        $ss = $em->getRepository('AppBundle:Suck')->findAll();
        /* origin */
        $output->writeln('count $entries:' . count($ss));
        $senses = [];
        foreach ($ss as $k => $s) {
            //$output->writeln("\n" . $k . '---------------         ' . $s->getUrl() . '    ------------------------------');
            $senses[] = $this->getContainer()->get('app.suck_model')->htmlToArray($s->getHmtl());
        }

        $file = fopen($this->getContainer()->get('kernel')->getRootDir() . '/../doc/dictSource/arrayWr.json', "w");
        $output->writeln(fwrite($file, json_encode($senses, JSON_UNESCAPED_UNICODE)));
        fclose($file);
    }
}