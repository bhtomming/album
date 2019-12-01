<?php

namespace App\Controller;

use App\Entity\Navigation;
use App\Form\NavigationType;
use App\Repository\NavigationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/navigation")
 */
class NavigationController extends AbstractController
{
    /**
     * @Route("/", name="navigation_index", methods={"GET"})
     */
    public function index(NavigationRepository $navigationRepository): Response
    {
        return $this->render('navigation/index.html.twig', [
            'navigations' => $navigationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="navigation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $navigation = new Navigation();
        $form = $this->createForm(NavigationType::class, $navigation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($navigation);
            $entityManager->flush();

            return $this->redirectToRoute('navigation_index');
        }

        return $this->render('navigation/new.html.twig', [
            'navigation' => $navigation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="navigation_show", methods={"GET"})
     */
    public function show(Navigation $navigation): Response
    {
        return $this->render('navigation/show.html.twig', [
            'navigation' => $navigation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="navigation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Navigation $navigation): Response
    {
        $form = $this->createForm(NavigationType::class, $navigation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('navigation_index');
        }

        return $this->render('navigation/edit.html.twig', [
            'navigation' => $navigation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="navigation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Navigation $navigation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$navigation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($navigation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('navigation_index');
    }
}
