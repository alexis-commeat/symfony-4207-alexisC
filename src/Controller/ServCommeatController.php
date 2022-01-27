<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServCommeatController extends AbstractController
{
    /**
     * @Route("/serv/commeat", name="serv_commeat")
     */
    public function index(): Response
    {
        return $this->render('serv_commeat/index.html.twig', [
            'controller_name' => 'ServCommeatController',
        ]);
    }
}
