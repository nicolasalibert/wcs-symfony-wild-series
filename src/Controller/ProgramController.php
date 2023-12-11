<?php

namespace App\Controller;

use App\Repository\ProgramRepository;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;

use App\Service\ProgramDuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
// use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository, RequestStack $requestStack): Response
    {
        // $session = $requestStack->getSession();

        // if (!$session->has('total')) {
        //     $session->set('total', 0);
        // }
        // $total = $session->get('total');

        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'website'       => 'Wild Series',
            'programs'      => $programs,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, SluggerInterface $slugger): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                    ->from($this->getParameter('mailer_from'))
                    ->to('recipient@mail.com')
                    ->subject('Une nouvelle série vient d\'être publiée')
                    ->html($this->renderView('Program/newProgramEmail.html.twig', [
                        'program' => $program
                    ]));

            $mailer->send($email);

            $this->addFlash('success', 'The new program has been created');

            return $this->redirectToRoute('program_show', [
                'slug' => $program->getSlug()
            ]);
        }

        return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/show/{slug}', methods: ['GET'], name: 'show')]
    public function show(Program $program, ProgramDuration $programDuration, string $slug): Response
    {
        if(!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program->getId() . ' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $programDuration->calculate($program)
        ]);
    }

    #[Route('/{slug}/seasons/{season}', methods: ['GET'], name: 'season_show')]
    public function showSeason(Program $program, Season $season, string $slug): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    #[Route('/{programSlug}/season/{season}/episode/{episodeSlug}', methods: ['GET'], name: 'episode_show')]
    public function showEpisode(
        #[MapEntity(mapping: ['programSlug' => 'slug'])] Program $program,
        Season $season,
        #[MapEntity(mapping: ['episodeSlug' => 'slug'])] Episode $episode
        ): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
