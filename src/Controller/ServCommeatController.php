<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
Use App\Entity\Utilisateurs;

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
         * @Route("/newU", name="newU")
         */
        public function newU(): Response
        {
            return $this->render('serv_commeat/newU.html.twig', [
                'controller_name' => 'ServCommeatController',
            ]);
    }

    /**
         * @Route("/listeU", name="listeU")
         */
        public function listeU(EntityManagerInterface $manager): Response
        {
            $mesUtilisateurs=$manager->getRepository(Utilisateurs::class)->findAll();
            return $this->render('serv_commeat/listeU.html.twig',['lst_utilisateurs' => $mesUtilisateurs]);      
    }

    /**
         * @Route("/insertU", name="insertU")
         */
        public function insertU(Request $request, EntityManagerInterface $manager): Response
        {
            $login = $request->request->get("pseudo");
			$password = $request->request->get("pass");
            $password = password_hash($password, PASSWORD_DEFAULT);
            $monUtilisateur = new Utilisateurs ();
            $monUtilisateur -> setlogin($login);
            $monUtilisateur -> setpassword($password);
            $manager -> persist($monUtilisateur);
            $manager -> flush ();
            return $this->redirectToRoute ('listeU');
    }

    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion(Request $request, EntityManagerInterface $manager): Response
    {
			//récupération des information du formulaire.
			$login = $request->request->get("pseudo");
			$password = $request->request->get("pass");
            $reponse = $manager -> getRepository(Utilisateurs :: class) -> findOneBy([ 'login' => $login]);
            if($reponse==NULL)
                $message="utilisateur inconnu";
            else
                $hash = $reponse -> getPassword();
                if (password_verify($password,$hash))
                    $message = "login et mot de passe correct✅";
            else
                $message = "ATTENTION : mot de passe incorrect⛔";
        return $this->render('serv_commeat/connexion.html.twig', [
            'message'=> $message,
            'login'=> $login,
            'password'=> $password,
        ]);
    }
}
