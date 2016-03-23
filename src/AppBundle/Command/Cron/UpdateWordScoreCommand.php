<?php
namespace AppBundle\Command\Cron;

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

class UpdateWordScoreCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pebidi:updateWordScore');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $query = '
            UPDATE Word w SET w.score = (SELECT SUM(p.point)/COUNT(p.id) FROM Point p WHERE p.word_id = w.id);';
        $em = $this->getContainer()->get('doctrine');
        $connection = $em->getConnection();
        $stmt = $connection->prepare($query);
        $stmt->execute();
    }
}
