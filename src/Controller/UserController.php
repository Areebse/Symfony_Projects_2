<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
//    #[Route('/user/{user}', name: 'app_user')]
    public function index(Request $request, $user): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => $user,
        ]);
    }
}