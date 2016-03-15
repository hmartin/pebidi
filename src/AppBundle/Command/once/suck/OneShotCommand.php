<?php
namespace AppBundle\Command\once\suck;

use AppBundle\Entity\Suck;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class OneShotCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('oneShot:wordDefEn');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        //$p = $em->getRepository('MainDefaultBundle:Suck')->findAll();

        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT MAX(page) AS page FROM Suck LIMIT 1");
        $statement->execute();
        $results = $statement->fetchAll();

        $p = $results[0]['page'];
        $p++;
        $s = 'http://www.wordreference.com/2012/sitemap.aspx?dict=enfr&page=' . $p;
        $output->writeln('start '. $s);
        $curl_handle = curl_init();
        \curl_setopt($curl_handle, CURLOPT_URL, $s);
        \curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        \curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
        $html = \curl_exec($curl_handle);
        \curl_close($curl_handle);
        //var_dump($html);echo '<br><br><br>';
        $crawler = new Crawler($html);

        $links = $crawler->filter('#contenttable a')->links();
        //echo '<pre>';
        foreach ($links as $k => $l) {
            sleep(rand(0.1,1));
            if ($k < 2) {
                continue;
            }
            $curl_handle = curl_init();
            \curl_setopt($curl_handle, CURLOPT_URL, $l->getUri());
            \curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
            \curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            \curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
            $subhtml = \curl_exec($curl_handle);
            \curl_close($curl_handle);
            $crawler = new Crawler($subhtml);
            $output->writeln($l->getUri());
            $enWord = $crawler->filter('#articleWRD');
            if (count($enWord) && null === ($x = $em->getRepository('MainDefaultBundle:Suck')->findOneByUrl($l->getUri()))) {
                $suck = new Suck();
                $suck->setUrl($l->getUri());
                $suck->setPage($p);
                $suck->setHmtl($enWord->html());
                $em->persist($suck);

            }

        }
        $em->flush();

    }
}