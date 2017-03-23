<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Modeles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Modele controller.
 *
 * @Route("modeles")
 */
class ModelesController extends Controller
{
    /**
     * Lists all modele entities.
     *
     * @Route("/", name="modeles_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $modeles = $em->getRepository('AppBundle:Modeles')->findAll();

        return $this->render('modeles/index.html.twig', array(
            'modeles' => $modeles,
        ));
    }

    /**
     * Creates a new modele entity.
     *
     * @Route("/new", name="modeles_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $modele = new Modeles();
        $form = $this->createForm('AppBundle\Form\ModelesType', $modele);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($modele);
            $em->flush($modele);

            return $this->redirectToRoute('modeles_show', array('id' => $modele->getId()));
        }

        return $this->render('modeles/new.html.twig', array(
            'modele' => $modele,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a modele entity.
     *
     * @Route("/{id}", name="modeles_show")
     * @Method("GET")
     */
    public function showAction(Modeles $modele)
    {
        $deleteForm = $this->createDeleteForm($modele);

        return $this->render('modeles/show.html.twig', array(
            'modele' => $modele,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing modele entity.
     *
     * @Route("/{id}/edit", name="modeles_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Modeles $modele)
    {
        $deleteForm = $this->createDeleteForm($modele);
        $editForm = $this->createForm('AppBundle\Form\ModelesType', $modele);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('modeles_edit', array('id' => $modele->getId()));
        }

        return $this->render('modeles/edit.html.twig', array(
            'modele' => $modele,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a modele entity.
     *
     * @Route("/{id}", name="modeles_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Modeles $modele)
    {
        $form = $this->createDeleteForm($modele);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($modele);
            $em->flush($modele);
        }

        return $this->redirectToRoute('modeles_index');
    }

    /**
     * Creates a form to delete a modele entity.
     *
     * @param Modeles $modele The modele entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Modeles $modele)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('modeles_delete', array('id' => $modele->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
