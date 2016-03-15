<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;


class CreateJsonFromFolderCommand extends ContainerAwareCommand
{

    public $persistWords = array('en' => array(), 'fr' => array());
    public $type = array();

    protected function configure()
    {
        $this
            ->setName('pebidi:createJsonFromFolder');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();  
        $this->output = $output;
        $this->createJsonFromArray();
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
        $file = __DIR__ . '/../../../doc/got.json';
        unlink($file);

        $file = fopen($file, "w");
        fwrite($file, json_encode($array, JSON_UNESCAPED_UNICODE));
        fclose($file);
    }
}
