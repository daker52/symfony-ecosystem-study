<?php

namespace App\Command;

use App\Repository\StudyDayRepository;
use App\Repository\StudyTopicRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:study:list',
    description: 'Vypíše study topics z DB — filtr, JSON output (den 4)',
)]
final class StudyListCommand extends Command
{
    public function __construct(
        private StudyTopicRepository $topicRepository,
        private StudyDayRepository $dayRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('day', 'd', InputOption::VALUE_REQUIRED, 'Filtr podle čísla dne')
            ->addOption('json', 'j', InputOption::VALUE_NONE, 'Výstup jako JSON');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dayFilter = $input->getOption('day');
        $topics = $this->topicRepository->findLatest(50);

        if ($dayFilter !== null) {
            $day = $this->dayRepository->findByNumber((int) $dayFilter);
            if ($day === null) {
                $io->error(sprintf('Den %s neexistuje.', $dayFilter));

                return Command::FAILURE;
            }
            $topics = array_values(array_filter(
                $topics,
                static fn ($t) => $t->getStudyDay()->getNumber() === (int) $dayFilter,
            ));
        }

        if ($input->getOption('json')) {
            $data = array_map(static fn ($t) => [
                'slug' => $t->getSlug(),
                'title' => $t->getTitle(),
                'day' => $t->getStudyDay()->getNumber(),
            ], $topics);
            $io->writeln(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

            return Command::SUCCESS;
        }

        if ($topics === []) {
            $io->warning('Žádné topics — spusť app:study:seed');

            return Command::SUCCESS;
        }

        $rows = array_map(static fn ($t) => [
            $t->getStudyDay()->getNumber(),
            $t->getSlug(),
            $t->getTitle(),
        ], $topics);

        $io->table(['Den', 'Slug', 'Název'], $rows);

        return Command::SUCCESS;
    }
}
