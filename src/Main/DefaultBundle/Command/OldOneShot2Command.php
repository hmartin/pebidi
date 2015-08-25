<?php
namespace Main\DefaultBundle\Command;

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


class OldOneShot2Command extends InsertCommand
{
    protected function configure()
    {
        $this
            ->setName('oneShot:oldanalyzeSuck');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $time_start = microtime_float();

        $ss = $em->getRepository('MainDefaultBundle:Suck')->findAll();
        /* origin */
        foreach ($ss as $k => $s) {
            $time_end = microtime_float();
            $time = $time_end - $time_start;
            if ($time > 3000) {
                //$em->flush();
                //exit;
            }
            $newWord = false;
            $output->writeln("\n".$k . '---------------         ' . $s->getUrl() . '    ------------------------------');
            $crawler = new Crawler($s->getHmtl());
            $crawler = $crawler->filter('table.WRD > tr');
            $class = '';
            $k = 0;
            /* sence */
            foreach ($crawler as $domElement) {
                if ($domElement->getAttribute('class') == 'even' || $domElement->getAttribute('class') == 'odd') {
                    $tr = new Crawler($domElement);
                    if ($class != $domElement->getAttribute('class')) {
                        $k = $k + 0.1;
                        $priority = 0;
                        $class = $domElement->getAttribute('class');

                        if (!$newWord) {
                            if (null !== ($newWord = $tr->filter('strong')->eq(0)->html())) {
                                if ($em->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => utf8_decode(utf8_encode($newWord)), 'local' => 'en'))) {
                                    //echo
                                    // word already in db
                                    $w = null;
                                    //continue 2;
                                }
                                $newWord = explode(',', $newWord);
                                $w = $this->getWord($newWord['0'], 'en');

                            } else {
                                echo 'error' . $s->getUrl() . '<br>';
                            }
                        }

                        if ((null !== ($senseValue = $tr->filter('td')->eq(1))) && count($senseValue) > 0) {

                            $sense = new Sense();
                            $sense->setSense(utf8_decode($senseValue->html()));
                            $sense->setLocal('en');

                            $em->persist($sense);

                        }
                    }


                    if (null !== ($trans = $tr->filter('td.ToWrd')->eq(0))) {
                        if (null == $trans->filter('span[title*="translation unavailable"]')->eq(0)) {
                            continue;
                        }
                        $trans->filter('em')->each(function (Crawler $crawler) {
                            foreach ($crawler as $node) {
                                $node->parentNode->removeChild($node);
                            }
                        });
                        $trans->filter('a')->each(function (Crawler $crawler) {
                            foreach ($crawler as $node) {
                                $node->parentNode->removeChild($node);
                            }
                        });
                        if (count($trans)) {
                            $ws = explode(',', $trans->html());
                            foreach ($ws as $each) {
                                $priority = $priority + 1;
                                $prior = $priority + $k;

                                $tw = $this->getWord($each, 'fr');
                                //$output->writeln('c:' . $class . '   s:'.$sense.'   w:'. $w  .'   t:' . $tw . ' $prior:' . $prior );

                                $ww = new Ww();
                                $ww->setWord1($w);
                                $ww->setWord2($tw);
                                $ww->addSense($sense);
                                $ww->setPriority($prior);

                                $em->persist($ww);

                            }
                        }

                    }
                }
            }
        }
        //$em->flush();
        $x = 1 / 0;
        exit;
    }

}