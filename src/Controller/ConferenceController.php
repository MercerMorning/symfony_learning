<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ConferenceController extends AbstractController
{
    /**
     * @Route("/conferencies", name="homepage")
     */
    public function index(Environment $twig, ConferenceRepository $conferenceRepository): Response
    {
        return new Response($twig->render('conference/index.html.twig', [
                       'conferences' => $conferenceRepository->findAll(),
                   ]));
//        $greet = '';
//        if ($name) {
//            $greet = sprintf('<h1>Hello %s!</h1>', htmlspecialchars($name));
//        }
//        return new Response(<<<EOF
//            <html>
//             $greet
//                <body>
//                   <img src="/images/under-construction.gif" />
//                </body>
//            </html>
//EOF
//        );
    }

    public function config(Request $request): Response
    {

        return new Response(
            $this->generateUrl('homepage', ['name' => 'sdf'])
//            $request->attributes->get('_route')
        );
    }

    /**
     * @Route("/inject", name="un")
     */
    public function inject(MessageGenerator $messageGenerator): Response
    {
        return Response::create('123');
    }

    /**
     * @Route("/conference/{conference}", name="conference")
     */
    public function show(Request $request, Environment $twig, Conference $conference, CommentRepository $commentRepository): Response
    {
//        throw $this->createNotFoundException();
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);
//        return Response::create(11);
        return new Response($twig->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
        ]));
    }
}
