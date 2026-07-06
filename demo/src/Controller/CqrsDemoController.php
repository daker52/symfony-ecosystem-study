<?php

namespace App\Controller;

use App\Message\Command\CreateStudyTopicCommand;
use App\Message\Query\GetStudyTopicQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cqrs-demo')]
final class CqrsDemoController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private MessageBusInterface $queryBus,
    ) {
    }

    #[Route('', name: 'app_cqrs_demo')]
    public function page(): Response
    {
        return $this->render('cqrs/demo.html.twig');
    }

    #[Route('/api/topics', name: 'app_cqrs_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $title = trim((string) ($data['title'] ?? ''));
        $slug = trim((string) ($data['slug'] ?? ''));
        $body = trim((string) ($data['body'] ?? ''));
        $day = (int) ($data['day'] ?? 6);

        if ($title === '' || $slug === '' || $body === '') {
            return $this->json(['error' => 'title, slug, body required'], 400);
        }

        $this->commandBus->dispatch(new CreateStudyTopicCommand($title, $slug, $body, $day));

        return $this->json([
            'status' => 'accepted',
            'message' => 'Command odeslán do async fronty — spusť: php bin/console messenger:consume async -vv',
            'slug' => $slug,
        ], 202);
    }

    #[Route('/api/topics/{slug}', name: 'app_cqrs_get', methods: ['GET'])]
    public function get(string $slug): JsonResponse
    {
        $envelope = $this->queryBus->dispatch(new GetStudyTopicQuery($slug));
        $handled = $envelope->last(HandledStamp::class);
        $result = $handled?->getResult();

        if (!is_array($result)) {
            return $this->json(['error' => 'not found'], 404);
        }

        return $this->json($result);
    }
}
