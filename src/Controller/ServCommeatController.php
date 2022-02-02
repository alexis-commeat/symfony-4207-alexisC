<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ServCommeatController extends AbstractController
{
    /**
     * @Route("/commeat", name="serv_commeat")
     */
    public function index(): Response
    {
        return $this->render('serv_commeat/index.html.twig', [
            'controller_name' => 'ServCommeatController',
        ]);
    }
    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion(Request $request): Response
    {
			//récupération des information du formulaire.
			$login = $request->request->get("pseudo");
			$password = $request->request->get("pass");
            $message = $request->request->get("message");
            if ($login=="root" && $password=="toor")
            $message = "login et mot de passe correct✅";
            else
                $message = "ATTENTION : login et mot de passe incorrect⛔";
        return $this->render('serv_commeat/connexion.html.twig', [
            'login' => $login,
            'password' => $password,
            'message'=> $message,
		
        ]);
    }
}
