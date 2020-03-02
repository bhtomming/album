<?php

namespace App\Controller;

use App\Entity\Friendship;
use App\Form\FriendshipType;
use App\Repository\FriendshipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/friendship")
 */
class FriendshipController extends AbstractController
{


    /**
     * @Route("/new", name="friendship_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $friendship = new Friendship();
        $form = $this->createForm(FriendshipType::class, $friendship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($friendship);
            $entityManager->flush();

            return $this->redirectToRoute('friendship_index');
        }

        return $this->render('friendship/new.html.twig', [
            'friendship' => $friendship,
            'form' => $form->createView(),
        ]);
    }

}
