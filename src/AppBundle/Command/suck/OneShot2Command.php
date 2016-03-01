<?php
namespace AppBundle\Command\suck;

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
        foreach ($ss as $k => $s) {
            $time_end = microtime_float();
            $time = $time_end - $time_start;
            if ($time > 3000) {
                //$em->flush();
                //exit;
            }
            $output->writeln("\n" . $k . '---------------         ' . $s->getUrl() . '    ------------------------------');
            $senses = $this->getContainer()->get('app.wr_suck')->htmlToArray($s->getHmtl());
            $this->getContainer()->get('app.word_controller')->postImprove($senses);
        }

    }

    protected function clean()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("
         SET FOREIGN_KEY_CHECKS=0;
        TRUNCATE `Category`;
        TRUNCATE `DictionariesWord`;
        TRUNCATE `Dictionary`;
        TRUNCATE `DictionaryScore`;
        TRUNCATE `Point`;
        TRUNCATE `Result`;
        TRUNCATE `Sense`;
        TRUNCATE `Test`;
        TRUNCATE `TestWord`;
        TRUNCATE `User`;
        TRUNCATE `Word`;
        TRUNCATE `WordType`;
        TRUNCATE `Ww`;
        TRUNCATE `WwSenses`;
         SET FOREIGN_KEY_CHECKS=1;");
        $statement->execute();
        $statement->closeCursor();
    }
}