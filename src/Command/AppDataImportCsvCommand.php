<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\CsvLegoLoaderService;

class AppDataImportCsvCommand extends Command {

    protected static $defaultName = 'app:data:import-csv';
    protected $loader;

    public function __construct(CsvLegoLoaderService $loader) {
        $this->loader = $loader;
        parent::__construct();
    }

    protected function configure() {
        $this
                ->setDescription('Import the CSV files from the data directory')
                ->addOption('count', 'nu', InputOption::VALUE_OPTIONAL, 'Number of sets to import', 0)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input, $output);

        $end = $input->getOption('count');

        $count = $end ? $end : 'all';
        $io->writeln("Starting to import $count sets.");

        $numberAtOnce = 500;
        $start = 1;
        $sets = 1;
        $localStart = $start;
        $localEnd = $start;
        $io->progressStart();
        while ((!$end || $localEnd <= $end) && $sets) {
            $localStart = $localEnd;
            $localEnd += $numberAtOnce;
            if ($localEnd >= $end) {
                $localEnd = $end + 1;
            }
            $sets = $this->loader->loadSets($localStart, $localEnd);
            $setCount = is_array($sets) ? count($sets) : $sets;
            //$io->writeln("$setCount more sets loaded");
            $io->progressAdvance();
        }
        $io->progressFinish();
        $io->success("Successfully imported some sets");
    }

}