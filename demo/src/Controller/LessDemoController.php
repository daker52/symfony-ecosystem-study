<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LessDemoController extends AbstractController
{
    #[Route('/less-demo', name: 'app_less_demo')]
    public function page(): Response
    {
        return $this->render('less/demo.html.twig', [
            'rows' => [
                ['less' => '@color: #2a6f4e;', 'sass' => '$color: #2a6f4e;'],
                ['less' => '.card() { … } mixin', 'sass' => '@mixin card { … }'],
                ['less' => '&__title nesting', 'sass' => '&__title nesting (stejné)'],
                ['less' => 'JS-like výraz darken()', 'sass' => 'color.adjust() / darken()'],
            ],
        ]);
    }
}
