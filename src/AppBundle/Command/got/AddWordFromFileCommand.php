<?php
namespace AppBundle\Command\got;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;


class AddWordFromFileCommand extends ContainerAwareCommand
{

    public $persistWords = array('en' => array(), 'fr' => array());
    public $type = array();

    protected function configure()
    {
        $this
            ->setName('pebidi:addWordFromFile');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();  
        $this->output = $output;

        $file = file_get_contents(__DIR__ . '/../../../../doc/got.json');
        $flipped = json_decode($file, true);
        
        $output->writeLn(count($flipped));
        $found = $wrExist = $notFound = 0;
        foreach($flipped as $k => $fl) {
            
            if ($em->getRepository('AppBundle:Word')->findOneBy(array('word' => $k, 'local' => 'en'))) 
            {
                $found++;
                $output->writeLn( $found .' | found:' . $k);
                unset($flipped[$k]);
            } elseif ($senses = $this->getContainer()->get('app.suck_model')->wordToArray($k)) {
                $this->getContainer()->get('app.word_model')->postImprove($k, $senses);
                $wrExist++;
                $output->writeLn( $wrExist .' | Just add:' . $k);
                sleep(rand(0,2));
            } else {
                $notFound++;
                $output->writeLn( $notFound .' | not Found:' . $k);
                unset($flipped[$k]);
            }
            if ($wrExist > 10) {
                $em->flush();
                break;
            }
        }
        $output->writeLn( ' in db:'. $found .' exist on wr:'. $wrExist .' not found:'. $notFound);
        $em->flush();
        $this->reWriteFile($flipped);
    }
    
    protected function createJsonFromArray() 
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../../../doc/got');
        $str = '';
        foreach ($finder as $file) {
            
            $this->output ->writeLn($file->getRealpath());
            $str .= ' ' . file_get_contents($file->getRealpath());
        }
        $str = str_replace('--', '', $str);
        $str = str_replace('-', ' ', $str);
        $str = str_replace('\'', ' ', $str);
        $a = str_word_count($str, 1);
        $a = array_map('strtolower', $a);
        sort($a);
        $flipped = array_flip($a);
        foreach ($flipped as $key => $v) 
        {
            if (substr($key, -1) == 's' && array_key_exists (mb_substr($key, 0, -1), $flipped)){
                unset($flipped[$key]);
            } elseif (substr($key, -2) == 'ed' && array_key_exists (mb_substr($key, 0, -2), $flipped)){
                unset($flipped[$key]);
            } elseif (substr($key, -1) == 'd' && array_key_exists (mb_substr($key, 0, -1), $flipped)){
                unset($flipped[$key]);
            } elseif (substr($key, -3) == 'ing' && array_key_exists (mb_substr($key, 0, -3), $flipped)){
                unset($flipped[$key]);
            }
        }

        $this->reWriteFile($flipped);
    }

    protected function reWriteFile($array)
    {
        $file = __DIR__ . '/../../../../doc/got.json';
        unlink($file);

        $file = fopen($file, "w");
        fwrite($file, json_encode($array, JSON_UNESCAPED_UNICODE));
        fclose($file);
    }
}
