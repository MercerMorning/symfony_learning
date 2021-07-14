<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use App\Service\MessageGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ConferenceController extends AbstractController
{
    public $twig;
    public $entityManager;

    public function __construct(Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }


//    public function obrab(Request $request, )
//    {
//        $comment = new Comment();
//        $form = $this->createForm(CommentFormType::class, $comment);
//                $form->handleRequest($request);
//                if ($form->isSubmitted() && $form->isValid()) {
//                    $comment->setConference($conference);
//
//                    $this->entityManager->persist($comment);
//                    $this->entityManager->flush();
//
//                    return $this->redirectToRoute('conference', ['slug' => $conference->getSlug()]);
//        }
//    }
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
    public function show($photoDir, ConferenceRepository $conferenceRepository, Request $request, Environment $twig, Conference $conference, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setConference($conference);
            if ($photo = $form['photo']->getData()) {
                $filename = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();
                try {
                    $photo->move($photoDir, $filename);
                } catch (FileException $e) {
                    // unable to upload the photo, give up
                }
                $comment->setPhotoFilename($filename);
            }
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('conference', ['conference' => $conference->getId()]);
        }
//        throw $this->createNotFoundException();
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);
//        return Response::create(11);
        return new Response($this->twig->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset, CommentRepository::PAGINATOR_PER_PAGE),
            'conferences' => $commentRepository->findAll(),
            'form' => $form->createView(),
        ]));
    }
}
