<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/hello", name="hello_world")
     */
    public function helloAction()
    {
        return $this->render('AppBundle:Default:hello.html.twig');
    }

    /**
     * @Route("/page", name="page")
     */
    public function pageAction()
    {
        $data = array(
            'title' => 'title',
            'array' => array(
                'jour' => 'vendredi',
                'mois' => 'février',
            ),
        );
        return $this->render('AppBundle:Default:page.html.twig', $data);
    }
}
