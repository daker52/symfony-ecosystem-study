<?php

namespace App\Command;

use App\Entity\StudyDay;
use App\Entity\StudyTopic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:study:seed',
    description: 'Naplní DB ukázkovými study topics — preferuj doctrine:fixtures:load',
)]
final class StudySeedCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $repo = $this->entityManager->getRepository(StudyTopic::class);
        if ($repo->count([]) > 0) {
            $io->warning('DB už obsahuje topics — seed přeskočen. Použij: doctrine:fixtures:load');

            return Command::SUCCESS;
        }

        $days = [
            1 => new StudyDay(1, 'Symfony základy'),
            2 => new StudyDay(2, 'Services & DI'),
            3 => new StudyDay(3, 'Doctrine ORM'),
            4 => new StudyDay(4, 'Symfony Console'),
            5 => new StudyDay(5, 'Design patterns'),
        ];

        foreach ($days as $day) {
            $this->entityManager->persist($day);
        }

        $topics = [
            ['routing-v-symfony', 'Routing v Symfony', 'Attributes na controlleru, debug:router.', $days[1]],
            ['twig-sablony', 'Twig šablony', 'extends, block, path() — analogie k Blade.', $days[1]],
            ['di-services-yaml', 'DI a services.yaml', 'Autowiring, interface binding, parametry.', $days[2]],
            ['compiler-pass', 'Compiler pass', 'Tagy a automatická registrace služeb.', $days[2]],
            ['doctrine-entity', 'Doctrine entity', 'persist() + flush(), repository, migrace.', $days[3]],
        ];

        foreach ($topics as [$slug, $title, $body, $day]) {
            $this->entityManager->persist(new StudyTopic($title, $slug, $body, $day));
        }

        $this->entityManager->flush();

        $io->success('Study topics seeded.');

        return Command::SUCCESS;
    }
}
