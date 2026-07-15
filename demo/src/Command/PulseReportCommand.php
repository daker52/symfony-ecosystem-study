<?php

namespace App\Command;

use App\Repository\WorkOrderRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:pulse:report',
    description: 'Pulse — přehled Work Orders a fronty (den 14)',
)]
final class PulseReportCommand extends Command
{
    public function __construct(
        private WorkOrderRepository $workOrderRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $stats = $this->workOrderRepository->countByStatus();

        $io->title('Pulse report');
        $io->definitionList(
            ['queued' => (string) $stats['queued']],
            ['running' => (string) $stats['running']],
            ['done' => (string) $stats['done']],
            ['failed' => (string) $stats['failed']],
            ['total' => (string) $stats['total']],
        );

        $rows = [];
        foreach ($this->workOrderRepository->findLatest(10) as $order) {
            $rows[] = [
                (string) $order->getId(),
                $order->getTitle(),
                $order->getType(),
                $order->getStatus(),
                $order->getCurrentStage() ?? '—',
            ];
        }

        if ($rows === []) {
            $io->note('Žádné work orders. Vytvoř je na /pulse (JWT).');
        } else {
            $io->table(['ID', 'Title', 'Type', 'Status', 'Stage'], $rows);
        }

        $io->writeln('Worker: <info>php bin/console messenger:consume async -vv</info>');

        return Command::SUCCESS;
    }
}
