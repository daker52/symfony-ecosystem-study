<?php

namespace App\Controller;

use App\Message\Command\CreateWorkOrderCommand;
use App\Message\Query\ListWorkOrdersQuery;
use App\Repository\WorkOrderRepository;
use App\Service\WorkOrderSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PulseController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private MessageBusInterface $queryBus,
        private WorkOrderRepository $workOrderRepository,
        private WorkOrderSerializer $serializer,
    ) {
    }

    #[Route('/pulse', name: 'app_pulse')]
    public function page(): Response
    {
        return $this->render('pulse/demo.html.twig');
    }

    #[Route('/api/pulse/orders', name: 'api_pulse_orders_list', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function list(): JsonResponse
    {
        $envelope = $this->queryBus->dispatch(new ListWorkOrdersQuery());
        $handled = $envelope->last(HandledStamp::class);
        /** @var list<array<string, mixed>> $orders */
        $orders = $handled?->getResult() ?? [];

        return $this->json([
            'orders' => $orders,
            'stats' => $this->workOrderRepository->countByStatus(),
        ]);
    }

    #[Route('/api/pulse/orders', name: 'api_pulse_orders_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $title = trim((string) ($data['title'] ?? ''));
        $type = trim((string) ($data['type'] ?? 'generic'));

        if ($title === '') {
            return $this->json(['error' => 'title required'], Response::HTTP_BAD_REQUEST);
        }

        $allowed = ['cache-rebuild', 'digest', 'sync-topics', 'generic'];
        if (!in_array($type, $allowed, true)) {
            $type = 'generic';
        }

        $envelope = $this->commandBus->dispatch(new CreateWorkOrderCommand($title, $type));
        $handled = $envelope->last(HandledStamp::class);
        $id = $handled?->getResult();

        return $this->json([
            'status' => 'accepted',
            'id' => $id,
            'hint' => 'Spusť worker: php bin/console messenger:consume async -vv',
        ], Response::HTTP_ACCEPTED);
    }

    #[Route('/api/pulse/orders/{id}', name: 'api_pulse_orders_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function show(int $id): JsonResponse
    {
        $order = $this->workOrderRepository->find($id);
        if ($order === null) {
            return $this->json(['error' => 'not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->serializer->toArray($order));
    }
}
