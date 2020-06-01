<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ConferenceController extends AbstractController
{

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @param Environment $twig
     * @param ConferenceRepository $conferenceRepository
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(
        Request $request,
        ConferenceRepository $conferenceRepository
    ) {
        return new Response(
            $this->twig->render(
                'conference/index.html.twig',
                [
                    'conferences' => $conferenceRepository->findAll(),
                ]
            )
        );
    }


    /**
     * @Route("/conference/{id}", name="conference")
     * @param Request $request
     * @param Conference $conference
     * @param CommentRepository $commentRepository
     * @param ConferenceRepository $conferenceRepository
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function show(
        Request $request,
        Conference $conference,
        CommentRepository $commentRepository,
        ConferenceRepository $conferenceRepository

    ) {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator(
            $conference,
            $offset
        );


        return new Response(
            $this->twig->render(
                'conference/show.html.twig',
                [
                    'conferences' => $conferenceRepository->findAll(),
                    'conference' => $conference,
                    'comments' => $paginator,
                    'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
                    'next' => min(
                        count($paginator),
                        $offset,
                        CommentRepository::PAGINATOR_PER_PAGE
                    ),
                ]
            )
        );
    }
}
