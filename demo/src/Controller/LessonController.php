<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LessonController extends AbstractController
{
    #[Route('/study/{topic}', name: 'app_study_topic', requirements: ['topic' => '[a-z0-9\-]+'])]
    public function topic(string $topic): Response
    {
        $labels = [
            'routing' => 'Routing',
            'controller' => 'Controller',
            'twig' => 'Twig šablony',
        ];

        return $this->render('lesson/topic.html.twig', [
            'topic' => $topic,
            'label' => $labels[$topic] ?? ucfirst(str_replace('-', ' ', $topic)),
        ]);
    }

    #[Route('/api/ping', name: 'app_api_ping', methods: ['GET'])]
    public function ping(): JsonResponse
    {
        return $this->json([
            'status' => 'ok',
            'framework' => 'symfony',
            'day' => 1,
        ]);
    }
}
