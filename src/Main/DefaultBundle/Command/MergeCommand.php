<?php
namespace Main\DefaultBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

use Main\DefaultBundle\Entity\Sense;
use Main\DefaultBundle\Entity\Word;
use Main\DefaultBundle\Entity\WordType;
use Main\DefaultBundle\Entity\Ww;
use Main\DefaultBundle\Entity\Category;
use Symfony\Component\Stopwatch\Stopwatch;

class MergeCommand extends ContainerAwareCommand
{

    public $persistWords = array('en' => array(), 'fr' => array());
    public $persistWordsType = array('en' => array(), 'fr' => array());
    public $persistCategorys = array();
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
        $stopwatch = new Stopwatch();
        $stopwatch->start('eventName');
        
        $result = array_merge_recursive($a3, $a1, $a2);

        echo 'merge: ' . count($result) . "\n";
        $i = 0;
        foreach ($result as $k => $w) {

            $t = $this->getType($w);
            
            $fromWordType = $this->getWordType($k, 'en', $t);

            foreach ($w['senses'] as $k => $senseArray) {
                if (isset($senseArray['s'])) {
                    $sense = new Sense();
                    $sense->setSense($senseArray['s']);
                    $sense->setLocal('en');

                    $em->persist($sense);

                    foreach ($senseArray['t'] as $k => $trans) {
                        $t = $this->getType($trans);
                        $transWordType = $this->getWordType($k, 'fr', $t);
                        $ww = new Ww();
                        $ww->setWord1($fromWordType);
                        $ww->setWord2($transWordType);
                        $ww->addSense($sense);
                        $ww->setPriority(0);

                        $em->persist($ww);

                    }
                }
            }
            $i++;
            if ($i > 100000000) {
                break;
            }
        }
        $output->writeln('Let\'s flush');
        $em->flush();
        $event = $stopwatch->stop('eventName');
        $m = $event->getDuration() / 1000 / 60;
        $output->writeln('Let\'s flush' . $m);
    }

private function getType($w) {
    
            $t = 'undef';
            if (isset($w['type']) && is_array($w['type'])) {
                foreach ($w['type'] as $type) {
                    if (!empty($type)) {
                        $t = $type;
                        break;
                    }
                }
            }
    
    return $t;
}
    protected function getWordType($w, $local, $type)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $category = $this->getCategory($type);

        if (!isset($this->persistWordsType[$local][$type])) {
            $this->persistWordsType[$local][$type] = array();
        }
        $wordString = $w;
        $kExplode = explode(' ', $w);
        if (count($kExplode) > 1) {
            $additional = true;
            $wordString = $kExplode['0'];
        }

        $word = $this->getWord($wordString, $local);
        if (array_key_exists($w, $this->persistWordsType[$local][$type])) {
            return $this->persistWordsType[$local][$type][$w];
        }
        $obj = new WordType();
        $obj->setWord($word);
        $obj->setCategory($category);
        if ($wordString != $w) {
            $obj->setExpression($w);
        }

        $em->persist($obj);
        $this->persistWordsType[$local][$type][$w] = $obj;
        //$em->flush();

        return $obj;
    }

    protected function getWord($w, $local)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        if (array_key_exists($w, $this->persistWords[$local])) {
            //echo 'PrExi: ' . $this->persistWords[$local][$w];
            return $this->persistWords[$local][$w];

        }

        //echo 'NoExi word: ' . $w;

        $obj = new Word();
        $obj->setLocal($local);
        $obj->setWord($w);
        $em->persist($obj);
        $this->persistWords[$local][$w] = $obj;

        return $obj;
    }

    protected function getCategory($c)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        if (array_key_exists($c, $this->persistCategorys)) {

            return $this->persistCategorys[$c];

        }

        echo 'NoExi cat: ' . $c ."\n";

        $obj = new Category();
        $obj->setCategory($c);
        $em->persist($obj);
        $this->persistCategorys[$c] = $obj;

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


    /*if ($obj = $em->getRepository('MainDefaultBundle:Word')->findOneBy(array('word' => $w, 'local' => $local))) {
        echo 'Exist: ' . $obj->getWord();
        return $obj;
    } else */

}