<?php

namespace App\Controller;

use App\Contract\GreetingGeneratorInterface;
use App\Service\GreetingRegistry;
use App\Service\StudyInfoProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceDemoController extends AbstractController
{
    public function __construct(
        private GreetingGeneratorInterface $greetingGenerator,
        private GreetingRegistry $greetingRegistry,
        private StudyInfoProvider $studyInfo,
    ) {
    }

    #[Route('/services-demo', name: 'app_services_demo')]
    public function demo(): Response
    {
        return $this->render('service/demo.html.twig', [
            'greeting' => $this->greetingGenerator->greet('studující'),
            'allGreetings' => $this->greetingRegistry->greetAll('studující'),
            'registeredGenerators' => $this->greetingRegistry->getImplementationNames(),
            'study' => $this->studyInfo->toArray(),
        ]);
    }

    #[Route('/api/greeting/{name}', name: 'app_api_greeting', requirements: ['name' => '[a-zA-Z0-9\-]+'])]
    public function greeting(string $name): JsonResponse
    {
        return $this->json([
            'message' => $this->greetingGenerator->greet($name),
            'study' => $this->studyInfo->toArray(),
        ]);
    }
}
