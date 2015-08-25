<?php
namespace Main\DefaultBundle\Command;

use Main\DefaultBundle\Entity\Suck;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class mergeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('oneShot:merge');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $a1 = $this->deocde('/../dictSource/xdxf/eng-fra.json');
        $a2 = $this->deocde('/../dictSource/eng-fra/eng-fra.json');
        $a3 = $this->deocde('/../dictSource/WP_eng-fra.json');



        foreach($a1 as $k => $w) {
            echo $k."\n";
        }


    }


    private function deocde($filepath)
    {
        $file = file_get_contents($this->getContainer()->get('kernel')->getRootDir() . $filepath);
        $a = json_decode($file, true);
        echo $filepath.': '.count($a)."\n";

        return $a;

    }
}