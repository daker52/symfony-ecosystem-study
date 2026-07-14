<?php

namespace App\Controller;

use App\Repository\StudyTopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VueDemoController extends AbstractController
{
    #[Route('/vue-demo', name: 'app_vue_demo')]
    public function page(): Response
    {
        return $this->render('vue/demo.html.twig');
    }

    #[Route('/api/study/topics', name: 'app_api_study_topics', methods: ['GET'])]
    public function topics(StudyTopicRepository $topicRepository): JsonResponse
    {
        $topics = [];
        foreach ($topicRepository->findLatest(50) as $topic) {
            $topics[] = [
                'slug' => $topic->getSlug(),
                'title' => $topic->getTitle(),
                'body' => $topic->getBody(),
                'day' => $topic->getStudyDay()->getNumber(),
            ];
        }

        return $this->json([
            'topics' => $topics,
            'count' => count($topics),
        ]);
    }
}
