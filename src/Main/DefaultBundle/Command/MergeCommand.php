<?php
namespace Main\DefaultBundle\Command;

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

class MergeCommand extends ContainerAwareCommand
{

    public $persistWords = array('en' => array(), 'fr' => array());
    public $persistWordsType = array('en' => array(), 'fr' => array());
    public $type = array();

    protected function configure()
    {
        $this
            ->setName('oneShot:merge');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $this->clean();
        $a1 = $this->deocde('/../dictSource/xdxf/eng-fra.json');
        $a2 = $this->deocde('/../dictSource/eng-fra/eng-fra.json');
        $a3 = $this->deocde('/../dictSource/WP_eng-fra.json');

        $result = array_merge_recursive($a1, $a2, $a3);

        echo 'merge: ' . count($result) . "\n";

        foreach ($result as $k => $w) {
            $fromWordType = $this->getWordType($k, 'en');

            foreach ($w['senses'] as $k => $senseArray) {
                if (isset($senseArray['s'])) {
                    $sense = new Sense();
                    $sense->setSense($senseArray['s']);
                    $sense->setLocal('en');

                    $em->persist($sense);

                    foreach ($senseArray['t'] as $k => $trans) {
                        $transWordType = $this->getWordType($k, 'fr');
                        $ww = new Ww();
                        $ww->setWord1($fromWordType);
                        $ww->setWord2($transWordType);
                        $ww->addSense($sense);
                        $ww->setPriority(0);

                        $em->persist($ww);

                    }
                }
            }
        }
        $output->writeln('Let\s flush');
        $em->flush();

    }


    protected function getWordType($w, $local, $type = null)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        if (empty($type)) {
            $type = 'null';
        }
        $wordString = $w;
        $kExplode = explode(' ', $w);
        if (count($kExplode) > 1) {
            $additional = true;
            $wordString = $kExplode['0'];    
        }
        
        $word = $this->getWord($wordString, $local);
        
        if (array_key_exists($w, $this->persistWords[$local][$type])) {
            return $this->persistWords[$local][$type][$w];
        }

        $obj = new WordType();
        $obj->setWord($word);
        if ($wordString  != $w) {
            $obj->setExpression($w);
        }
        
        $em->persist($obj);
        if (!isset( $this->persistWordsType[$local][$type])) {
             $this->persistWordsType[$local][$type] = array();
        }
        $this->persistWordsType[$local][$type][$w] = $obj;
        //$em->flush();

        return $obj;
    }
    
    protected function getWord($w, $local)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
            
        if (array_key_exists($w, $this->persistWords[$local])) {
            echo 'PrExi: ' . $this->persistWords[$local][$w];
            return $this->persistWords[$local][$w];

        }

        echo 'NoExi: ' . $w;

        $obj = new Word();
        $obj->setLocal($local);
        $obj->setWord($w);
        $em->persist($obj);
        $this->persistWords[$local][$w] = $obj;

        return $obj;
    }



    private function deocde($filepath)
    {
        $file = file_get_contents($this->getContainer()->get('kernel')->getRootDir() . $filepath);
        $a = json_decode($file, true);
        echo $filepath . ': ' . count($a) . "\n";

        return $a;

    }

    protected function clean()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("
         SET FOREIGN_KEY_CHECKS=0;
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
        TRUNCATE `Ww`;
        TRUNCATE `WwSenses`;
         SET FOREIGN_KEY_CHECKS=1;");
        $statement->execute();
        $statement->closeCursor();
    }
    

        /*if ($obj = $em->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => $w, 'local' => $local))) {
            echo 'Exist: ' . $obj->getWord();
            return $obj;
        } else */
            
}