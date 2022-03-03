<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateurs;
use App\Entity\Document;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
     * @Route("/ajoutFichier", name="ajoutFichier")
     */
    public function ajoutFichier(): Response
    {
        return $this->render('/ajoutFichier.html.twig', [
            'controller_name' => 'ServCommeatController',
        ]);
    }

     /**
     * @Route("/insertF", name="insertF")
     */
    public function insertF(): Response
    {
        $tmp_name = $_FILES["ajoutFichier"]["tmp_name"];
        $name = basename($_FILES["ajoutFichier"]["name"]);
        move_uploaded_file($tmp_name, "home/etudrt/servcommeat/public/$name");
        return $this->render('/ajoutFichier.html.twig');
    }

    /**
    * @Route("/listeF", name="listeF")
    */
   public function listeF(EntityManagerInterface $manager, SessionInterface $session): Response
   {
       $vs = $session -> get('identifiant');
       if($vs==NULL)
           return $this->redirectToRoute ('serv_commeat');
       else{
           $mesDocument=$manager->getRepository(Document::class)->findAll();
           return $this->render('/listeF.html.twig',['lst_document' => $mesDocument]); 
       }     
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
         * @Route("/Deconnexion", name="Deconnexion")
         */
        public function Deconnexion(EntityManagerInterface $manager, SessionInterface $session): Response
        {
        $session->clear();
        return $this->redirectToRoute ('serv_commeat');
        }

    /**
         * @Route("/listeU", name="listeU")
         */
        public function listeU(EntityManagerInterface $manager, SessionInterface $session): Response
        {
            $vs = $session -> get('identifiant');
            if($vs==NULL)
                return $this->redirectToRoute ('serv_commeat');
            else{
                $mesUtilisateurs=$manager->getRepository(Utilisateurs::class)->findAll();
                return $this->render('serv_commeat/listeU.html.twig',['lst_utilisateurs' => $mesUtilisateurs]); 
            }     
    }

    
/**
* @Route("/supprimerU/{id}",name="supprimerU")
*/
public function supprimerU(EntityManagerInterface $manager,Utilisateurs $editutil): Response {
    $manager->remove($editutil);
    $manager->flush();
    // Affiche de nouveau la liste des utilisateurs
    return $this->redirectToRoute ('listeU');
 }

 
    /**
         * @Route("/insertU", name="insertU")
         */
        public function insertU(Request $request, EntityManagerInterface $manager, SessionInterface $session): Response
        {
            $login = $request->request->get("pseudo");
			$password = $request->request->get("pass");
            $password = password_hash($password,PASSWORD_DEFAULT);
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
    public function connexion(Request $request, SessionInterface $session, EntityManagerInterface $manager): Response
    {
			//récupération des information du formulaire.
			$login = $request->request->get("pseudo");
			$password = $request->request->get("pass");
            $reponse = $manager -> getRepository(Utilisateurs :: class) -> findOneBy([ 'login' => $login]);
            if($reponse==NULL){
                $message="utilisateur inconnu";
                $session -> clear();
    }else {
                $hash = $reponse -> getPassword();
                if (password_verify($password, $hash)){
                    $message = "Connexion Reussie✅";
                    $session -> set('identifiant',$reponse -> getId() );
                }else{
                    $message = "ATTENTION : mot de passe incorrect⛔";
                    
                    $session -> clear();
            }
    }

        return $this->render('serv_commeat/connexion.html.twig', [
            'message'=> $message,
            'login'=> $login,
            'password'=> $password,
        ]);
    }
}
