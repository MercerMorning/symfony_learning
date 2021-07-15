<?php

namespace App\Controller;

use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecondSecurityController extends AbstractController
{

    /**
     * @Route("/logins", name="login")
     */
    public function loginAction(Request $request)
    {

    }

    /**
     * @Route("/second/security", name="second_security")
     */
    public function index(): Response
    {
        return $this->render('second_security/index.html.twig', [
            'controller_name' => 'SecondSecurityController',
        ]);
    }
}
