<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'title' => 'Symfony study — den 2',
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        $topics = [
            'Dependency Injection & services.yaml',
            'Autowiring, parametry, interface binding',
            'Twig, routing (den 1)',
        ];

        return $this->render('home/about.html.twig', [
            'topics' => $topics,
            'startedAt' => '2026-06-30',
        ]);
    }

    #[Route('/laravel-map', name: 'app_laravel_map')]
    public function laravelMap(): Response
    {
        $mapping = [
            ['laravel' => 'routes/web.php', 'symfony' => 'config/routes.yaml + PHP attributes'],
            ['laravel' => 'app/Http/Controllers', 'symfony' => 'src/Controller'],
            ['laravel' => 'resources/views (Blade)', 'symfony' => 'templates/ (Twig)'],
            ['laravel' => 'app/Providers', 'symfony' => 'config/services.yaml'],
            ['laravel' => 'public/index.php', 'symfony' => 'public/index.php'],
            ['laravel' => 'php artisan', 'symfony' => 'php bin/console'],
        ];

        return $this->render('home/laravel_map.html.twig', [
            'mapping' => $mapping,
        ]);
    }

    #[Route('/api/version', name: 'app_api_version', methods: ['GET'])]
    public function version(): JsonResponse
    {
        return $this->json([
            'php' => PHP_VERSION,
            'symfony' => Kernel::VERSION,
            'app' => 'symfony-ecosystem-study',
            'day' => 2,
        ]);
    }
}
