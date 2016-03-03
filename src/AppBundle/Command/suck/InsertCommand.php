<?php
namespace AppBundle\Command\suck;

use AppBundle\Entity\Sense;
use AppBundle\Entity\Suck;
use AppBundle\Entity\Word;
use AppBundle\Entity\Ww;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

abstract class InsertCommand extends ContainerAwareCommand
{

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
        TRUNCATE `Test`;
        TRUNCATE `TestWord`;
        TRUNCATE `User`;
        TRUNCATE `Word`;
        TRUNCATE `SubWord`;
        TRUNCATE `Ww`;
         SET FOREIGN_KEY_CHECKS=1;");
        $statement->execute();
        $statement->closeCursor();
    }

    protected function cleanString($string)
    {
        if (mb_detect_encoding($string) != 'UTF-8') {
            $string = iconv('ASCII', 'UTF-8', $string);
        }

        if (substr_count($string, ' ') > 1 or $this->starts_with_upper($string) ) {
            return null;
        }
        $endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
        $string = str_replace('*', '', $string);
        $string = str_replace('...', '', $string);
        $string = str_replace('‐', '-', $string);
        $string = str_replace('<br>', '', $string);
        $string = str_replace('<span title="something">[sth]</span>', '[sth]', $string);
        $string = str_replace('<span title="somebody">[sb]</span>', '[sb]', $string);
        $string = str_replace('<span title="somebody or something">[sb/sth]</span>', '[sb/sth]', $string);

        if (!preg_match('/^[-\'\p{L}\p{M}\s-]+$/u', $string)) {
            echo "\n". 'not accepted: '. $string;
            return null;
        }

        return trim($string);
    }

    private function starts_with_upper($str) 
    {
        $chr = mb_substr ($str, 0, 1, "UTF-8");
        return mb_strtolower($chr, "UTF-8") != $chr;
    }
    
}