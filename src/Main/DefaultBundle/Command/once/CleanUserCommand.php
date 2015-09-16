<?php
namespace Main\DefaultBundle\Command\once;

use Main\DefaultBundle\Entity\Sense;
use Main\DefaultBundle\Entity\Suck;
use Main\DefaultBundle\Entity\Word;
use Main\DefaultBundle\Entity\Ww;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class CleanUserCommand extends ContainerAwareCommand
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        var_dump(set_time_limit(60 * 60*5));
        $connection = $em->getConnection();
        $statement = $connection->prepare("
         SET FOREIGN_KEY_CHECKS=0;
            TRUNCATE `DictionariesWord`;
            TRUNCATE `Dictionary`;
            TRUNCATE `DictionaryScore`;
            TRUNCATE `User`;
            TRUNCATE `Point`;
            TRUNCATE `Result`;
            TRUNCATE `Test`;
            TRUNCATE `TestWord`;
         SET FOREIGN_KEY_CHECKS=1;");
        $statement->execute();
        $statement->closeCursor();

    }
}