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

class JsonCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('oneShot:json');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queryGroup = 'SELECT  w.id, w.word as w, substring_index(group_concat(w2.word SEPARATOR ", "), ", ", 4) as t FROM Word w
               JOIN WordType wt ON wt.word_id = w.id AND wt.expression IS NULL
               JOIN Ww ww ON  ww.word1_id = wt.id
               JOIN WordType wt2 ON ww.word2_id = wt2.id
               JOIN Word w2 ON wt2.word_id = w2.id
               WHERE w.local = "en" GROUP BY w.id';
        $em = $this->getContainer()->get('doctrine');
        $connection = $em->getConnection();
        $stmt = $connection->prepare($queryGroup);
        $stmt->execute();

        $results = $stmt->fetchAll();
        $file = fopen(__DIR__ . '/../../../../web/dict/dict.json', "w");
        echo fwrite($file, json_encode($results));
        fclose($file);
    }
}