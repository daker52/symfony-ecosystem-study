<?php

namespace App\Command;

use App\Factory\GreetingGeneratorFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:greet',
    description: 'Pozdrav přes Factory + Strategy (den 5)',
)]
final class GreetCommand extends Command
{
    public function __construct(
        private GreetingGeneratorFactory $greetingFactory,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Jméno')
            ->addOption('style', 's', InputOption::VALUE_REQUIRED, 'formal | casual', 'formal')
            ->addOption('no-log', null, InputOption::VALUE_NONE, 'Vypnout Decorator logging');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = (string) $input->getArgument('name');
        $style = (string) $input->getOption('style');

        $generator = $this->greetingFactory->create($style, !$input->getOption('no-log'));
        $io->writeln($generator->greet($name));

        return Command::SUCCESS;
    }
}
