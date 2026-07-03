<?php

namespace App\Service;

/**
 * Přehled design patterns použitých v demo — pro studijní stránku.
 */
final class PatternCatalog
{
    /**
     * @return list<array{pattern: string, where: string, laravel: string}>
     */
    public function all(): array
    {
        return [
            [
                'pattern' => 'Strategy',
                'where' => 'FormalGreetingGenerator / CasualGreetingGenerator + interface',
                'laravel' => 'různé implementace za stejným kontraktem',
            ],
            [
                'pattern' => 'Factory',
                'where' => 'GreetingGeneratorFactory::create()',
                'laravel' => 'Factory třídy / app()->make()',
            ],
            [
                'pattern' => 'Decorator',
                'where' => 'LoggingGreetingGeneratorDecorator obalí generator',
                'laravel' => 'middleware / wrapper services',
            ],
            [
                'pattern' => 'Repository',
                'where' => 'StudyTopicRepository, StudyDayRepository',
                'laravel' => 'Eloquent + custom query scopes',
            ],
            [
                'pattern' => 'Observer',
                'where' => 'StudyTopicSubscriber — Doctrine postPersist',
                'laravel' => 'Model events (creating, created)',
            ],
            [
                'pattern' => 'Command',
                'where' => 'app:hello, app:study:list, app:greet — bin/console',
                'laravel' => 'Artisan commands',
            ],
        ];
    }
}
