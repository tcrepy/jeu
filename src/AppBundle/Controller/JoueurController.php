<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Entity\Parties;
use AppBundle\Entity\Cartes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class DefaultController
 * @package AppBundle\Controller
 * @Route("joueur")
 */
class JoueurController extends Controller
{

    /**
     * @Route("/", name="joueur_homepage")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        return $this->render("joueur/index.html.twig", ['user' => $user]);
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
     * @Route("/parties/add", name="joueur_parties_add")
     */

    public function addPartieAction()
    {
        $user = $this->getUser();
        // récupérer tous les joueurs existants
        $joueurs = $this->getDoctrine()->getRepository('AppBundle:Users')->findAll();
        return $this->render("joueur/addPartie.html.twig", ['user' => $user, 'joueurs' => $joueurs]);
    }

    /**
     * @param Users $id
     * @Route("/inviter/{joueur}", name="creer_partie")
     */
    public function creerPartieAction(Users $joueur)
    {

        $user = $this->getUser();

        $partie = new Parties();

        $partie->setUsers1($user);
        $partie->setUsers2($joueur);

        $em = $this->getDoctrine()->getManager();
        $em->persist($partie);
        $em->flush();

        // récupérer les cartes
        $modeles = $this->getDoctrine()->getRepository('AppBundle:Modeles')->findAll();
        //on mélange les cartes
        shuffle($modeles);

        for($i = 0; $i<8; $i++)
        {
            $cartes = new Cartes();
            $cartes->setParties($partie);

            $cartes->setModeles($modeles[$i]);
            $cartes->setCarteSituation('mainJ1');

            $em->persist($cartes);
        }

        for($i = 8; $i<16; $i++)
        {
            $cartes = new Cartes();
            $cartes->setParties($partie);

            $cartes->setModeles($modeles[$i]);
            $cartes->setCarteSituation('mainJ2');

            $em->persist($cartes);
        }

        for($i = 16; $i<count($modeles); $i++)
        {
            $cartes = new Cartes();
            $cartes->setParties($partie);

            $cartes->setModeles($modeles[$i]);
            $cartes->setCarteSituation('pioche');

            $em->persist($cartes);
        }

        $em->flush();

        return $this->render('joueur/partie.html.twig', ['partie' => $partie, 'user' => $user]);
    }

    /**
     * @param Parties $id
     * @Route("/afficher/{id}", name="afficher_partie")
     */
    public function afficherPartieAction(Parties $id)
    {
        $cartes = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findAll();
        $partie = $this->getDoctrine()->getRepository('AppBundle:Parties')->findBy(['id' => $id]);
        $user = $this->getUser();

        return $this->render(':joueur:afficherpartie.html.twig', ['cartes' => $cartes, 'parties' => $partie, 'user' => $user]);
    }
}