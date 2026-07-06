<?php

namespace App\DataFixtures;

use App\Entity\StudyDay;
use App\Entity\StudyTopic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Naplní DB ukázkovými study topics — ekvivalent app:study:seed, ale přes Doctrine Fixtures.
 */
final class StudyDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $days = [
            1 => new StudyDay(1, 'Symfony základy'),
            2 => new StudyDay(2, 'Services & DI'),
            3 => new StudyDay(3, 'Doctrine ORM'),
            4 => new StudyDay(4, 'Symfony Console'),
            5 => new StudyDay(5, 'Design patterns'),
            6 => new StudyDay(6, 'DDD + CQRS'),
            7 => new StudyDay(7, 'Fixtures & async'),
        ];

        foreach ($days as $day) {
            $manager->persist($day);
        }

        $topics = [
            ['routing-v-symfony', 'Routing v Symfony', 'Attributes na controlleru, debug:router.', $days[1]],
            ['twig-sablony', 'Twig šablony', 'extends, block, path() — analogie k Blade.', $days[1]],
            ['di-services-yaml', 'DI a services.yaml', 'Autowiring, interface binding, parametry.', $days[2]],
            ['compiler-pass', 'Compiler pass', 'Tagy a automatická registrace služeb.', $days[2]],
            ['doctrine-entity', 'Doctrine entity', 'persist() + flush(), repository, migrace.', $days[3]],
            ['console-commands', 'Console commands', 'app:hello, argumenty, options, DI v commandu.', $days[4]],
            ['design-patterns', 'Design patterns', 'Strategy, Factory, Decorator v demo.', $days[5]],
            ['cqrs-messenger', 'CQRS + Messenger', 'command.bus, query.bus, async transport.', $days[6]],
            ['doctrine-fixtures', 'Doctrine Fixtures', 'doctrine:fixtures:load místo ručního seedu.', $days[7]],
        ];

        foreach ($topics as [$slug, $title, $body, $day]) {
            $manager->persist(new StudyTopic($title, $slug, $body, $day));
        }

        $manager->flush();
    }
}
