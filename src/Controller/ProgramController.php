<?php

namespace App\Controller;

use App\Repository\ProgramRepository;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($program);
            $entityManager->flush();

            $this->addFlash('success', 'The new program has been created');

            return $this->redirectToRoute('program_show', [
                'id' => $program->getId()
            ]);
        }

        return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/show/{id<\d+>}', methods: ['GET'], name: 'show')]
    public function show(Program $program): Response
    {
        // $program = $programRepository->findOneBy(['id' => $id]); // same
        // $program = $programRepository->findOneById($id);         // same
        // $program = $programRepository->find($id);                // same

        // if(!$program) {
        //     throw $this->createNotFoundException(
        //         'No program with id : ' . $id . ' found in program\'s table.'
        //     );
        // }

        return $this->render('program/show.html.twig', [
            'program' => $program
        ]);
    }

    #[Route('/{program}/seasons/{season}', methods: ['GET'], name: 'season_show')]
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    #[Route('/program/{program}/season/{season}/episode/{episode}', methods: ['GET'], name: 'episode_show')]
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
