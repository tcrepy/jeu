<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Default:index.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/parties/", name="joueur_parties")
     */
    public function mesPartiesAction()
    {
        $user = $this->getUser();
        return $this->render("joueur/mesparties.html.twig", ['user' => $user]);
    }

    /**
     * @Route("/partie/add", name="jouer")
     */
    public function addPartieAction()
    {
        $user = $this->getUser();

        // récupérer tous les joueurs existants
        $joueurs = $this->getDoctrine()->getRepository('AppBundle:Joueurs')->findAll();

        return $this->render("joueur/addPartie.html.twig", ['user' => $user, 'joueurs' => $joueurs]);
    }

    public function afficherPartieAction(Partie $id)
    {

    }
}
