<?php

namespace App\DependencyInjection\Compiler;

use App\Service\GreetingRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Při kompilaci containeru najde všechny služby s tagem app.greeting_generator
 * a předá je do GreetingRegistry.
 */
final class GreetingGeneratorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(GreetingRegistry::class)) {
            return;
        }

        $references = [];
        foreach ($container->findTaggedServiceIds('app.greeting_generator') as $id => $tags) {
            $references[] = new Reference($id);
        }

        $container->getDefinition(GreetingRegistry::class)
            ->setArgument('$generators', $references);
    }
}
