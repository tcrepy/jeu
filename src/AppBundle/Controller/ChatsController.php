<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Entity\Parties;
use AppBundle\Entity\Chats;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ChatsController extends Controller
{
    /**
     * @param Parties $id
     * @Route("/lirechat/{id}", name="lireChat")
     */
    public function lireAction(Parties $id)
    {
        $message = $this->getDoctrine()->getRepository('AppBundle:Chats')->findBy(['parties' => $id]);
        return $this->render('chats/afficherChat.html.twig', ['messages' => $message]);
    }

    /**
     * @param Request $request
     * @param Partie $parties
     * @Route("/posterchat/{parties}", name="posterChat")
     **/
    public function posterAction(Request $request, Parties $parties)
    {
        $user = $this->getUser();
        $messageReq = $request->request->all();
        $message = $messageReq['message'];

        $em = $this->getDoctrine()->getManager();

        $chat = new Chats();
        $chat->setParties($parties);
        $chat->setChatMessage($message);
        $chat->setUsers($user);
        $chat->setChatDate(date("Y-m-d H:i:s"));

        $em->persist($chat);
        $em->flush();
        return new Response();
    }

}

?>