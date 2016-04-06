<?php
namespace AppBundle\Command\got;

use AppBundle\Entity\DictionaryWord;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Dictionary;

class CreateGwFromListCommand extends ContainerAwareCommand
{

    public $persistWords = array('en' => array(), 'fr' => array());
    public $type = array();

    protected function configure()
    {
        $this
            ->setName('pebidi:createGwFromList');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();  
        $this->output = $output;

        $file = file_get_contents(__DIR__ . '/../../../../doc/got.json');
        $flipped = json_decode($file, true);
        
        $output->writeLn(count($flipped));
        $user = $em->getRepository('AppBundle:User')->find(1);

        $d = new Dictionary($user, 'fr', 'en');
        $d->transformToGroup(
            'Game of Thrones',
            'The only 5000 words used in the five first seasons!',
            0
        );
        $em->persist($d);
        
        $found = $wrExist = $notFound = 0;
        foreach($flipped as $k => $fl) 
        {
            if ($w = $em->getRepository('AppBundle:Word')->findOneBy(array('word' => $k, 'local' => 'en'))) 
            {
                $dw = new DictionaryWord($d, $w);
                $em->persist($dw);
                $found++;
            }
        }
        
        $output->writeLn( 'added in gw:'. $found);
        $em->flush();
    }
}
