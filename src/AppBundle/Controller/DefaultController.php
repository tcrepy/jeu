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
     * @Route("/classement", name="classement")
     */
    public function classementAction()
    {
        $classement = $this->getDoctrine()
            ->getRepository('AppBundle:Users')
            ->findBy(
                array(),
                array('score' => 'desc')
            );

        return $this->render('AppBundle:Default:classement.html.twig', ['classement' => $classement]);
    }

    /**
     * @Route("/regle", name="regle")
     */
    public function regleAction()
    {
        return $this->render('AppBundle:Default:regle.html.twig');
    }

    /**
     * @Route("/tutoriel", name="tutoriel")
     */
    public function tutorielAction()
    {
        return $this->render('AppBundle:Default:tutoriel.html.twig');
    }

    /**
     * @Route("/encyclopedie", name="encyclopedie")
     */
    public function encycloAction()
    {
        return $this->render('AppBundle:Default:encyclopedie.html.twig');
    }
}
