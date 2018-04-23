<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Set;

class AppDataRemoveDuplicatesCommand extends Command {

    protected static $defaultName = 'app:data:remove-duplicates';
    protected $em;

    public function __construct(EntityManagerInterface $em) {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure() {
        $this->setDescription('Purge duplicate sets from database');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input, $output);

        $qb = $this->em->createQueryBuilder()->select('s')->from(Set::class, 's')->groupBy('s.name, s.no')->having('COUNT(s) > 1');
        
        $purgeNo = 0;
        $rows = $qb->getQuery()->iterate();
        $io->progressStart();
        $unique = array();
        $batchSize = 50;
        $i = 0;
        foreach ($rows as $set) {
            $unique = true;
            if (array_key_exists($set->getNo(), $unique)) {
                if ($unique[$set->getNo()] == $set->getName()) {
                    $this->em->remove($set);
                    $unique = false;
                    $purgeNo++;
                }
            }
            if ($unique) {
                $unique[$set->getNo()] = $set->getName();
            }

            if (($i % $batchSize) === 0) {
                $this->em->flush(); // Executes all updates.
                $this->em->clear(); // Detaches all objects from Doctrine!
            }
            ++$i;
            $io->progressAdvance();
        }
        $io->progressFinish();
        $this->em->flush();

        $io->success("Purged $purgeNo duplicates from the database.");
    }

}