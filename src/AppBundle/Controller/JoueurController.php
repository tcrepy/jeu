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
            } elseif ($scorej2 > $scorej1) {
                $partie->setResultat($joueur2);
            } else {
                $partie->setResultat('Egalité');
            }

            foreach ($carte as $val) {
                $em->remove($val);
            }
        }

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
        //recup de la carte à jouer, et de sa catégorie
        $carteAJouer = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findOneBy(['id' => $carteid]);
        $categorie = $carteAJouer->getModeles()->getModeleCategorie();
        $valeur = $carteAJouer->getModeles()->getModeleValeur();

        $partie = $this->getDoctrine()->getRepository('AppBundle:Parties')->find($partieid);

        if ($partie->getPartieTour() == $partie->getUsers1()) {
            $score = $partie->getPartieJoueur1Score();

            //recup des cartes sur le plateau
            $cartesSurPlateau = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findBy(['carteSituation' => 'plateauj1', 'parties' => $partieid]);

            //si il y a des cartes sur le plateau
            if (!empty($cartesSurPlateau)) {
                $test = 0;
                $aEteJouee = 0;
                foreach ($cartesSurPlateau as $val) {
                    //si les catégories sont les mêmes et que la valeur de la carte jouée est supérieure à celle du plateau
                    if ($val->getModeles()->getModeleCategorie() == $categorie) {
                        if ($val->getModeles()->getModeleValeur() <= $valeur) {
                            $aEteJouee = 1;
                        }
                    } else {
                        //on incrémente si les catégories ne sont pas les mêmes
                        $test++;
                    }
                }
                if ($test == count($cartesSurPlateau) || $aEteJouee == 1) {
                    //on joue la carte
                    $em = $this->getDoctrine()->getManager();
                    $carteAJouer->setCarteSituation('plateauj1');
                    $partie->setJ1CarteJouer('1');

                    //on créer un multiplicateur egal à 1
                    $multiplicateur = 1;
                    if ($carteAJouer->getModeles()->getModeleExtra() == 1) {
                        $multiplicateur++;
                    }
                    //on l'incremente si il y a deja des extra de la même catégorie sur la table
                    foreach ($cartesSurPlateau as $val) {
                        if ($val->getModeles()->getModeleCategorie() == $categorie && $val->getModeles()->getModeleExtra() == 1) {
                            if ($carteAJouer->getModeles()->getModeleExtra() == 1) {
                                $score += -20;
                            }
                            $multiplicateur++;
                        }
                    }
                    if ($test == count($cartesSurPlateau)) {
                        $score += -20 * $multiplicateur;
                    }

                    $score += $carteAJouer->getModeles()->getModeleValeur() * $multiplicateur;

                    $partie->setPartieJoueur1Score($score);

                    $em->persist($partie, $carteAJouer);
                    $em->flush();
                }
            } else {
                //sinon on joue la carte
                $em = $this->getDoctrine()->getManager();
                $carteAJouer->setCarteSituation('plateauj1');
                $partie->setJ1CarteJouer('1');

                if ($carteAJouer->getModeles()->getModeleExtra() == 1) {
                    $score += -40;
                } else {
                    //on active une catégorie : -20
                    //on ajoute la valeur de la carte
                    $score += $carteAJouer->getModeles()->getModeleValeur();
                    $score += -20;
                }


                $partie->setPartieJoueur1Score($score);

                $em->persist($partie, $carteAJouer);
                $em->flush();
            }

        } else {
            $score = $partie->getPartieJoueur2Score();
            //recup des cartes sur le plateau
            $cartesSurPlateau = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findBy(['carteSituation' => 'plateauj2', 'parties' => $partieid]);

            //si il y a des cartes sur le plateau
            if (!empty($cartesSurPlateau)) {
                $test = 0;
                $aEteJouee = 0;
                foreach ($cartesSurPlateau as $val) {
                    //si les catégories sont les mêmes et que la valeur de la carte jouée est supérieure à celle du plateau
                    if ($val->getModeles()->getModeleCategorie() == $categorie) {
                        if ($val->getModeles()->getModeleValeur() <= $valeur) {
                            $aEteJouee = 1;
                        }
                    } else {
                        //on incrémente si les catégories ne sont pas les mêmes
                        $test++;
                    }
                }
                if ($test == count($cartesSurPlateau) || $aEteJouee == 1) {
                    //on joue la carte
                    $em = $this->getDoctrine()->getManager();
                    $carteAJouer->setCarteSituation('plateauj2');
                    $partie->setJ2CarteJouer('1');

                    //on créer un multiplicateur egal à 1
                    $multiplicateur = 1;
                    if ($carteAJouer->getModeles()->getModeleExtra() == 1) {
                        if ($carteAJouer->getModeles()->getModeleExtra() == 1) {
                            $score += -20;
                        }
                        $multiplicateur++;
                    }
                    //on l'incremente si il y a deja des extra de la même catégorie sur la table
                    foreach ($cartesSurPlateau as $val) {
                        if ($val->getModeles()->getModeleCategorie() == $categorie && $val->getModeles()->getModeleExtra() == 1) {
                            $multiplicateur++;
                        }
                    }
                    if ($test == count($cartesSurPlateau)) {
                        $score += -20 * $multiplicateur;
                    }

                    $score += $carteAJouer->getModeles()->getModeleValeur() * $multiplicateur;


                    $score += $carteAJouer->getModeles()->getModeleValeur() * $multiplicateur;

                    $partie->setPartieJoueur2Score($score);

                    $em->persist($partie, $carteAJouer);
                    $em->flush();
                }
            } else {
                //sinon on joue la carte
                $em = $this->getDoctrine()->getManager();
                $carteAJouer->setCarteSituation('plateauj2');
                $partie->setJ2CarteJouer('1');

                if ($carteAJouer->getModeles()->getModeleExtra() == 1) {
                    $score += -40;
                } else {
                    //on active une catégorie : -20
                    //on ajoute la valeur de la carte
                    $score += $carteAJouer->getModeles()->getModeleValeur();
                    $score += -20;
                }


                $partie->setPartieJoueur2Score($score);

                $em->persist($partie, $carteAJouer);
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
                $partie->setJ1CarteJouer('1');
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