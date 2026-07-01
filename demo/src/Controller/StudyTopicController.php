<?php

namespace App\Controller;

use App\Entity\StudyDay;
use App\Entity\StudyTopic;
use App\Repository\StudyDayRepository;
use App\Repository\StudyTopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/topics')]
final class StudyTopicController extends AbstractController
{
    public function __construct(
        private StudyTopicRepository $topicRepository,
        private StudyDayRepository $dayRepository,
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger,
    ) {
    }

    #[Route('', name: 'app_topics_index')]
    public function index(): Response
    {
        return $this->render('study/topics.html.twig', [
            'topics' => $this->topicRepository->findLatest(),
        ]);
    }

    #[Route('/new', name: 'app_topics_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $title = trim((string) $request->request->get('title', ''));
            $body = trim((string) $request->request->get('body', ''));
            $dayNumber = (int) $request->request->get('day', 3);

            if ($title !== '' && $body !== '') {
                $day = $this->dayRepository->findByNumber($dayNumber)
                    ?? new StudyDay($dayNumber, sprintf('Den %d', $dayNumber));

                if ($day->getId() === null) {
                    $this->entityManager->persist($day);
                }

                $slug = strtolower((string) $this->slugger->slug($title));
                $topic = new StudyTopic($title, $slug, $body, $day);

                $this->entityManager->persist($topic);
                $this->entityManager->flush();

                return $this->redirectToRoute('app_topics_show', ['slug' => $slug]);
            }
        }

        return $this->render('study/topic_new.html.twig', [
            'defaultDay' => 3,
        ]);
    }

    #[Route('/{slug}', name: 'app_topics_show')]
    public function show(string $slug): Response
    {
        $topic = $this->topicRepository->findBySlug($slug);
        if ($topic === null) {
            throw $this->createNotFoundException('Topic not found');
        }

        return $this->render('study/topic_show.html.twig', [
            'topic' => $topic,
        ]);
    }
}
