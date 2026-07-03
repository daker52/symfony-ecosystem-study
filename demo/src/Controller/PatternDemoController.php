<?php

namespace App\Controller;

use App\Factory\GreetingGeneratorFactory;
use App\Service\PatternCatalog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PatternDemoController extends AbstractController
{
    public function __construct(
        private PatternCatalog $patternCatalog,
        private GreetingGeneratorFactory $greetingFactory,
    ) {
    }

    #[Route('/patterns-demo', name: 'app_patterns_demo')]
    public function demo(): Response
    {
        $formal = $this->greetingFactory->create('formal');
        $casual = $this->greetingFactory->create('casual');

        return $this->render('pattern/demo.html.twig', [
            'patterns' => $this->patternCatalog->all(),
            'formalGreeting' => $formal->greet('daker52'),
            'casualGreeting' => $casual->greet('daker52'),
        ]);
    }
}
