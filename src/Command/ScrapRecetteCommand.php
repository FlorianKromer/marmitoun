<?php

namespace App\Command;

use App\Entity\Recette;
use Doctrine\Common\Persistence\ObjectManager;
use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ScrapRecetteCommand extends Command
{
    private $objectManager;
    private $progressBar;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
        // the name of the command (the part after "bin/console")
        ->setName('scrap:recette')

        // the short description shown while running "php bin/console list"
        ->setDescription('Scrap website to get recette.csv ')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to scrap website...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Début du Scrapping',
            '============',
            '',
        ]);
        $this->progressBar = new ProgressBar($output, 100);
        $this->progressBar->start();

        $recipes =
            $this->scrap('https://www.marmiton.org/recettes/top-internautes-entree.aspx');
        $output->writeln(['', \count($recipes).' recettes extraites']);
        foreach ($recipes as $r) {
            if (null !== $r) {
                $this->objectManager->persist($r);
            }
        }
        $this->objectManager->flush();
        $this->progressBar->finish();

        $output->writeln(\count($recipes).' recettes chargées');
    }

    public function scrap($url)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $recettes = $crawler->filterXPath('//a[contains(@class, "recipe-card-link")]')->each(function (Crawler $parent, $i) {
            // var_dump($parent->filterXPath('//h4[contains(@class, "recipe-card__description")]')->text());
            $recette = new Recette();
            $recette->setTitre($parent->filterXPath('//h4[contains(@class, "recipe-card__title")]')->text());
            $recette->setDescription($parent->filterXPath('//div[contains(@class, "recipe-card__description")]')->text());
            $recette->setImg($parent->filterXPath('//div[contains(@class, "recipe-card__picture")]/img')->attr('src'));
            $this->progressBar->advance();

            return $recette;
        });

        return $recettes;
    }
}
