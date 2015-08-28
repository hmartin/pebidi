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

        $ss = $em->getRepository('MainDefaultBundle:Suck')->findAll();
        /* origin */
        $output->writeln('count $entries:'. count($ss));
        foreach ($ss as $k => $s) {
            $time_end = microtime_float();
            $time = $time_end - $time_start;
            if ($time > 300) {
                //$em->flush();
                exit;
            }
            $newWord = false;
            //$output->writeln("\n".$k . '---------------         ' . $s->getUrl() . '    ------------------------------');
            $crawler = new Crawler($s->getHmtl());
            $crawler = $crawler->filter('table.WRD > tr');
            $class = '';
            $k = $i = 0;

            $arrayTrans = $senses = array();
            /* sence */
            foreach ($crawler as $domElement) {
                $sense = '';
                $senses = array();
                if ($domElement->getAttribute('class') == 'even' || $domElement->getAttribute('class') == 'odd') {
                    $tr = new Crawler($domElement);
                    if ($class != $domElement->getAttribute('class')) {
                        $k = $k + 0.1;
                        $priority = 0;
                        $class = $domElement->getAttribute('class');

                        if (!$newWord) {
                            if (null !== ($newWord = $tr->filter('strong')->eq(0)->html())) {
                                $t = '';
                                if (null !== ($type = $tr->filter('em')->eq(0))) {

                                    $type->filter('span')->each(function (Crawler $crawler) {
                                        foreach ($crawler as $node) {
                                            $node->parentNode->removeChild($node);
                                        }
                                    });
                                    $t = $type->html();

                                }
                                $newWord = explode(',', $newWord);
                                $w = $this->cleanString(utf8_decode($newWord['0']));

                            } else {
                                echo 'error' . $s->getUrl() . '<br>';
                            }
                        }

                        if ((null !== ($senseValue = $tr->filter('td')->eq(1))) && count($senseValue) > 0) {

                            $senseValue->filter('span')->each(function (Crawler $crawler) {
                                foreach ($crawler as $node) {
                                    $node->parentNode->removeChild($node);
                                }
                            });
                            $senseValue->filter('i')->each(function (Crawler $crawler) {
                                foreach ($crawler as $node) {
                                    $node->parentNode->removeChild($node);
                                }
                            });
                            $sensesArrayValue = explode(',', $senseValue->html());
                            $sense = $this->cleanSense(utf8_decode($sensesArrayValue['0']));

                        }
                    }


                    if (null !== ($trans = $tr->filter('td.ToWrd')->eq(0))) {
                        if (null == $trans->filter('span[title*="translation unavailable"]')->eq(0)) {
                            continue;
                        }
                        
                        $t_trans = '';
                        if (null !== ($type_trans = $trans->filter('em')->eq(0))) {

                            $type_trans->filter('span')->each(function (Crawler $crawler) {
                                foreach ($crawler as $node) {
                                    $node->parentNode->removeChild($node);
                                }
                            });
                            $t_trans = $type->html();
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

                                $tw = $this->cleanString(utf8_decode($each));
                                //$output->writeln('c:' . $class . '   s:'.$sense.'   w:'. $w  .'   t:' . $tw . ' $prior:' . $prior );
                                if ($tw) {
                                    $arrayTrans[$tw] = array('type' => $t_trans);
                                }

                            }
                        }

                    }

                    if (count($arrayTrans) > 0) {
                        $senses[] = array('s' => $sense, 't' => $arrayTrans);
                    }
                }
            }

            if ($w && count($senses) > 0) {
                $g = array('type' => $t, 'senses' => $senses);
                $global[$w] = $g;
            }
        }
        
        $output->writeln('count array:'. count($global));
        $file = fopen($this->getContainer()->get('kernel')->getRootDir() . '/../dictSource/WP_eng-fra.json', "w");
        $output->writeln(fwrite($file, json_encode($global)));
        fclose($file);
    }



    protected function cleanSense($string)
    {
        $string = str_replace('(', '', $string);
        $string = str_replace(')', '', $string);

        if (!preg_match('/^[\p{L}-\s\-\']*$/u', $string)) {
            //echo "\n". 'wrong sense' .$string;
            return null;
        }

        return trim($string);
    }

}