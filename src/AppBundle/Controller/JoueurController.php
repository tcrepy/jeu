<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Entity\Parties;
use AppBundle\Entity\Cartes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class DefaultController
 * @package AppBundle\Controller
 * @Route("joueur")
 */
class JoueurController extends Controller
{
    function finPartie($partieid)
    {
        $partie = $this->getDoctrine()->getRepository('AppBundle:Parties')->find($partieid);
        $joueur1 = $partie->getUsers1();
        $joueur2 = $partie->getUsers2();
        $carte = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findBy(['parties' => $partieid]);
        $cartePioche = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findBy(['parties' => $partieid, 'carteSituation' => 'pioche']);
        $em = $this->getDoctrine()->getManager();

        $nbCarte = count($cartePioche);

        if ($nbCarte == 1) {

            $scorej1 = $partie->getPartieJoueur1Score();
            $scorej2 = $partie->getPartieJoueur2Score();

            if ($scorej1 > $scorej2) {
                $partie->setResultat($joueur1);
                $user = $this->getDoctrine()->getRepository('AppBundle:Users')->find($joueur1);
                $user->setScore($partie->getPartieJoueur1Score());
            } elseif ($scorej2 > $scorej1) {
                $partie->setResultat($joueur2);
                $user = $this->getDoctrine()->getRepository('AppBundle:Users')->find($joueur2);
                $user->setScore($partie->getPartieJoueur2Score());
            } else {
                $partie->setResultat('Egalité');
            }

            foreach ($carte as $val) {
                $em->remove($val);
            }
        }
        $em->persist($joueur1, $joueur2);
        $em->flush();

    }

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
        $partie->setPartieJoueur1Score(0);
        $partie->setPartieJoueur2Score(0);

        $partie->setPartieTour($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($partie);
        $em->flush();

        // récupérer les cartes
        $modeles = $this->getDoctrine()->getRepository('AppBundle:Modeles')->findAll();
        //on mélange les cartes
        shuffle($modeles);

        for ($i = 0; $i < 8; $i++) {
            $cartes = new Cartes();
            $cartes->setParties($partie);

            $cartes->setModeles($modeles[$i]);
            $cartes->setCarteSituation('mainJ1');

            $em->persist($cartes);
        }

        for ($i = 8; $i < 16; $i++) {
            $cartes = new Cartes();
            $cartes->setParties($partie);

            $cartes->setModeles($modeles[$i]);
            $cartes->setCarteSituation('mainJ2');

            $em->persist($cartes);
        }

        for ($i = 16; $i < count($modeles); $i++) {
            $cartes = new Cartes();
            $cartes->setParties($partie);

            $cartes->setModeles($modeles[$i]);
            $cartes->setCarteSituation('pioche');

            $em->persist($cartes);
        }

        $em->flush();

//        return $this->render('joueur/partie.html.twig', ['partie' => $partie, 'user' => $user]);
        return $this->redirectToRoute('afficher_partie', ['id' => $partie->getId()]);
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

    /**
     * @param Parties $partieid
     * @Route("/piocher/{partieid}", name="piocher")
     */
    public function piocherAction($partieid)
    {
        $cartesPioche = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findOneBy(['carteSituation' => 'pioche', 'parties' => $partieid]);
        $partie = $this->getDoctrine()->getRepository('AppBundle:Parties')->find($partieid);
        $em = $this->getDoctrine()->getManager();

        if ($partie->getPartieTour() == $partie->getUsers1()) {
            $cartesPioche->setCarteSituation('mainJ1');
            $partie->setPartieTour($partie->getUsers2());
            $partie->setJ1CarteJouer('0');
        } else {
            $cartesPioche->setCarteSituation('mainJ2');
            $partie->setPartieTour($partie->getUsers1());
            $partie->setJ2CarteJouer('0');
        }
        $em->flush();
        $this->finPartie($partieid);
        return $this->redirectToRoute('afficher_partie', ['id' => $partieid]);
    }

    /**
     * @param Parties $partieid Cartes $carteid
     * @Route("/jouer/{partieid}/{carteid}", name="jouerCarte")
     **/
    public function jouerCarteAction($partieid, $carteid)
    {
        $cartejouer = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findOneBy(['id' => $carteid]);
        $jouer = $this->getDoctrine()->getRepository('AppBundle:Parties')->findOneBy(['id' => $partieid]);
        $categorie = $cartejouer->getModeles()->getModeleCategorie();
        $valeur = $cartejouer->getModeles()->getModeleValeur();
        if ($jouer->getPartieTour() == $jouer->getUsers1()){
            $cartesplateau = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findBy(['carteSituation' => 'plateauj1', 'parties' => $partieid]);
            $score = $jouer->getPartieJoueur1Score();
            if (!empty($cartesplateau)) {
                $remplis = 0;
                $etejouer = 0;
                foreach ($cartesplateau as $val) {
                    if ($val->getModeles()->getModeleCategorie() == $categorie) {
                        if ($val->getModeles()->getModeleValeur() <= $valeur) {
                            $etejouer = 1;
                        }
                    } else {
                        //si les categories sont differentes
                        $remplis++;
                    }
                }
                //si rempli == nombre de carte sur le plateau => il y a aucune carte de meme categorie
                if ($remplis == count($cartesplateau) || $etejouer == 1) {
                    $em = $this->getDoctrine()->getManager();
                    $cartejouer->setCarteSituation('plateauj1');
                    $jouer->setJ1cartejouer('1');
                    $multiplicateur = 1;
                    if ($cartejouer->getModeles()->getModeleExtra() == 1) {
                        $multiplicateur++;
                    }
                    foreach ($cartesplateau as $val) {
                        if ($val->getModeles()->getModeleCategorie() == $categorie && $val->getModeles()->getModeleExtra() == 1) {
                            if ($cartejouer->getModeles()->getModeleExtra() == 1) {
                                $score += -20;
                            }
                            $multiplicateur++;
                        }
                    }
                    if ($remplis == count($cartesplateau)) {
                        $score += -20 * $multiplicateur;
                    }
                    $score += $cartejouer->getModeles()->getModeleValeur() * $multiplicateur;
                    $jouer->setPartieJoueur1Score($score);
                    $em->flush();
                }
            } else {
                $em = $this->getDoctrine()->getManager();
                $cartejouer->setCarteSituation('plateauj1');
                $jouer->setJ1cartejouer('1');
                if ($cartejouer->getModeles()->getModeleExtra() == 1) {
                    $score += -40;
                } else {
                    $score += $cartejouer->getModeles()->getModeleValeur();
                    $score += -20;
                }
                $jouer->setPartieJoueur1Score($score);
                $em->flush();
            }
        } elseif ($jouer->getPartieTour() == $jouer->getUsers2()){
            $cartesplateau = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findBy(['carteSituation' => 'plateauj2', 'parties' => $partieid]);
            $score = $jouer->getPartieJoueur2Score();
            if (!empty($cartesplateau)) {
                $remplis = 0;
                $etejouer = 0;
                foreach ($cartesplateau as $val) {
                    if ($val->getModeles()->getModeleCategorie() == $categorie) {
                        if ($val->getModeles()->getModeleValeur() <= $valeur) {
                            $etejouer = 1;
                        }
                    } else {
                        $remplis++;
                    }
                }
                if ($remplis == count($cartesplateau) || $etejouer == 1) {
                    $em = $this->getDoctrine()->getManager();
                    $cartejouer->setCarteSituation('plateauj2');
                    $jouer->setJ2CarteJouer('1');
                    $multiplicateur = 1;
                    if ($cartejouer->getModeles()->getModeleExtra() == 1) {
                        $multiplicateur++;
                    }
                    foreach ($cartesplateau as $val) {
                        if ($val->getModeles()->getModeleCategorie() == $categorie && $val->getModeles()->getModeleExtra() == 1) {
                            if ($cartejouer->getModeles()->getModeleExtra() == 1) {
                                $score += -20;
                            }
                            $multiplicateur++;
                        }
                    }
                    if ($remplis == count($cartesplateau)) {
                        $score += -20 * $multiplicateur;
                    }
                    $score += $cartejouer->getModeles()->getModeleValeur() * $multiplicateur;
                    $jouer->setPartieJoueur2Score($score);
                    $em->flush();
                }
            } else {
                $em = $this->getDoctrine()->getManager();
                $cartejouer->setCarteSituation('plateauj2');
                $jouer->setJ2cartejouer('1');
                if ($cartejouer->getModeles()->getModeleExtra() == 1) {
                    $score += -40;
                } else {
                    $score += $cartejouer->getModeles()->getModeleValeur();
                    $score += -20;
                }
                $jouer->setPartieJoueur2Score($score);
                $em->flush();
            }
        }
        return $this->redirectToRoute('afficher_partie', ['id' => $partieid]);
    }

    /**
     * @param Parties $partieid Cartes $carteid
     * @Route("/defausse/{partieid}/{carteid}", name="defausserCarte")
     */
    public
    function defausseAction($partieid, $carteid)
    {
        //on recupere la partie, la carte à défausser, sa categorie et les cartes deja dans la défausse
        $partie = $this->getDoctrine()->getRepository('AppBundle:Parties')->find($partieid);
        $carteADefausser = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findOneBy(['id' => $carteid]);
        $carteDansDefausse = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findBy(['carteSituation' => 'defausse', 'parties' => $partieid]);

        $em = $this->getDoctrine()->getManager();

        //si la défausse n'est pas vide
        if (!empty($carteDansDefausse)) {
            $ordre = count($carteDansDefausse) + 1;
            $carteADefausser->setCarteSituation('defausse');
            $carteADefausser->setCarteOrdre($ordre);
            if ($partie->getPartieTour() == $partie->getUsers1()) {
                $partie->setJ1CarteJouer('1');
            } else {
                $partie->setJ2CarteJouer('1');

            }
        } else {
            $carteADefausser->setCarteSituation('defausse');
            $carteADefausser->setCarteOrdre(1);
            if ($partie->getPartieTour() == $partie->getUsers1()) {
                $partie->setJ1CarteJouer('1');
            } else {
                $partie->setJ2CarteJouer('1');
            }
        }

        $em->flush();

        return $this->redirectToRoute('afficher_partie', ['id' => $partieid]);
    }

    /**
     * @param Parties $partieid Cartes $carteid
     * @Route("/recup/{partieid}/{carteid}", name="recupererDefausse")
     */
    public
    function recupAction($partieid, $carteid)
    {
        $partie = $this->getDoctrine()->getRepository('AppBundle:Parties')->find($partieid);
        $cartesrecup = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findOneBy(['id' => $carteid]);
        $em = $this->getDoctrine()->getManager();
        $cartesrecup->setCarteOrdre(NULL);
        if ($partie->getPartieTour() == $partie->getUsers1()) {
            $cartesrecup->setCarteSituation('mainJ1');
            $partie->setPartieTour($partie->getUsers2());
            $partie->setJ1CarteJouer('0');
        } else {
            $cartesrecup->setCarteSituation('mainJ2');
            $partie->setPartieTour($partie->getUsers1());
            $partie->setJ2CarteJouer('0');
        }
        $em->flush();
        return $this->redirectToRoute('afficher_partie', ['id' => $partieid]);
    }
}