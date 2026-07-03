<?php

namespace App\Command;

use App\Contract\GreetingGeneratorInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:hello',
    description: 'Pozdrav přes DI — argument, options (den 4)',
)]
final class HelloCommand extends Command
{
    public function __construct(
        private GreetingGeneratorInterface $greetingGenerator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Koho pozdravit', 'studující')
            ->addOption('shout', null, InputOption::VALUE_NONE, 'Pozdrav velkými písmeny')
            ->addOption('upper', 'u', InputOption::VALUE_NONE, 'Alias pro --shout');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = (string) $input->getArgument('name');
        $message = $this->greetingGenerator->greet($name);

        if ($input->getOption('shout') || $input->getOption('upper')) {
            $message = mb_strtoupper($message);
        }

        $io->success($message);

        return Command::SUCCESS;
    }
}
