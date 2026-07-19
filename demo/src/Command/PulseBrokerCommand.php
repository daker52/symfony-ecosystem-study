<?php

namespace App\Command;

use App\Messenger\Transport\AmqpLibTransport;
use App\Messenger\Transport\AmqpSimTransport;
use App\Service\RabbitTopologyExporter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Transport\TransportInterface;

#[AsCommand(
    name: 'app:pulse:broker',
    description: 'Pulse Broker Passport — topologie, pending, export definitions.json (den 15)',
)]
final class PulseBrokerCommand extends Command
{
    public function __construct(
        #[Autowire(service: 'messenger.transport.async')]
        private TransportInterface $asyncTransport,
        private RabbitTopologyExporter $exporter,
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
        #[Autowire('%env(MESSENGER_TRANSPORT_DSN)%')]
        private string $dsn,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('export', null, InputOption::VALUE_NONE, 'Zapíše RabbitMQ definitions.json');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Pulse · Broker Passport');

        $io->definitionList(
            ['DSN' => $this->maskDsn($this->dsn)],
            ['Transport class' => $this->asyncTransport::class],
        );

        $topology = $this->resolveTopology();
        $io->section('Topologie (exchange → queue)');
        $io->writeln(sprintf(
            '  <info>%s</info>  --[%s]-->  <comment>%s</comment>',
            $topology['exchange'],
            $topology['routing_key'],
            $topology['queue'],
        ));
        $io->writeln(sprintf('  pending messages: <info>%d</info>', $topology['pending']));

        if ($input->getOption('export')) {
            $path = $this->projectDir.'/var/rabbit-definitions.json';
            $this->exporter->writeJson([
                'vhost' => $topology['vhost'] ?? '/',
                'exchange' => $topology['exchange'],
                'queue' => $topology['queue'],
                'routing_key' => $topology['routing_key'],
            ], $path);
            $io->success('Export: '.$path);
            $io->note('Rabbit UI → Import definitions (až poběží Docker broker).');
        }

        $io->writeln('');
        $io->writeln('Přepnutí na živý Rabbit:');
        $io->writeln('  1) docker compose -f docker-compose.rabbitmq.yml up -d');
        $io->writeln('  2) MESSENGER_TRANSPORT_DSN=amqplib://guest:guest@127.0.0.1:5672/%2f?exchange=pulse.work&queue=pulse.async&routing_key=work.advance');
        $io->writeln('  3) php bin/console messenger:consume async -vv');

        return Command::SUCCESS;
    }

    /**
     * @return array{exchange: string, queue: string, routing_key: string, pending: int, vhost?: string, host?: string}
     */
    private function resolveTopology(): array
    {
        if ($this->asyncTransport instanceof AmqpSimTransport) {
            return $this->asyncTransport->topologySnapshot();
        }
        if ($this->asyncTransport instanceof AmqpLibTransport) {
            return $this->asyncTransport->topologySnapshot();
        }

        return [
            'exchange' => 'n/a',
            'queue' => 'n/a (doctrine/other)',
            'routing_key' => 'n/a',
            'pending' => 0,
        ];
    }

    private function maskDsn(string $dsn): string
    {
        return (string) preg_replace('#://([^:]+):([^@]+)@#', '://$1:***@', $dsn);
    }
}
