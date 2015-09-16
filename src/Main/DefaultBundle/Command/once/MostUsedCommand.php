<?php
namespace Main\DefaultBundle\Command\once;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

use Main\DefaultBundle\Entity\Sense;
use Main\DefaultBundle\Entity\Suck;
use Main\DefaultBundle\Entity\Word;
use Main\DefaultBundle\Entity\Ww;

class MostUsedCommand extends ContainerAwareCommand
{

    public $persistWords = array('en' => array(), 'fr' => array());
    public $type = array();

    protected function configure()
    {
        $this
            ->setName('oneShot:20k');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $handle = fopen($this->getContainer()->get('kernel')->getRootDir() . "/../dictSource/20k.txt", "r");
        $i = $l = 0;
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $l++;
                $line = trim($line);
                if (strlen($line) > 1) {
                    if ((null == ($word = $em->getRepository('MainDefaultBundle:Word')->findBy(array('word' => $line))))
                        || (substr($line, -1) == 's'
                            && null == ($word = $em->getRepository('MainDefaultBundle:Word')->findBy(array('word' => substr($line, 0, -1)))))
                        || (substr($line, -3) == 'ies'
                            && null == ($word = $em->getRepository('MainDefaultBundle:Word')->findBy(array('word' => substr($line, 0, -3) . 'y'))))
                    ) {
                        $i++;
                        $output->writeln($line);
                        $words[] = $line;

                    }
                }
            }

            fclose($handle);
        }

        echo $i . '/' . $l;
        $file = fopen($this->getContainer()->get('kernel')->getRootDir() . '/../dictSource/20kout.json', "w");
        $output->writeln(fwrite($file, json_encode($words)));
        fclose($file);
    }
}
