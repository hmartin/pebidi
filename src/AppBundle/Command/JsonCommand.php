<?php
namespace AppBundle\Command;

use AppBundle\Entity\Suck;
use AppBundle\Entity\Sense;
use AppBundle\Entity\Word;
use AppBundle\Entity\Ww;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class JsonCommand extends ContainerAwareCommand
{
    protected $lang = ['en', 'fr'];

    protected function configure()
    {
        $this
            ->setName('oneShot:json');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->lang as $l) {
            $queryGroup = 'SELECT  w.id, w.word as w, substring_index(group_concat(w2.word SEPARATOR ", "), ", ", 4) as t FROM Word w
               JOIN SubWord sb ON sb.word_id = w.id AND sb.expression IS NULL
               JOIN Ww ww ON  ww.word1_id = sb.id
               JOIN SubWord sb2 ON ww.word2_id = sb2.id
               JOIN Word w2 ON sb2.word_id = w2.id
               WHERE w.local = "' . $l . '" GROUP BY w.id';
            $em = $this->getContainer()->get('doctrine');
            $connection = $em->getConnection();
            $stmt = $connection->prepare($queryGroup);
            $stmt->execute();

            $results = $stmt->fetchAll();
            $file = fopen(__DIR__ . '/../../../web/dict/dict' . $l . '.json', "w");
            $output->writeLn(date("Y-m-d h:i:sa") . ' dict' . $l . '.json' . fwrite($file, json_encode($results)));
            fclose($file);
        }
    }
}