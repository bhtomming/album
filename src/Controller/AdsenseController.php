<?php

namespace App\Controller;

use App\Entity\Adsense;
use App\Form\AdsenseType;
use App\Repository\AdsenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/adsense")
 */
class AdsenseController extends AbstractController
{
    /**
     * @Route("/", name="adsense_index", methods={"GET"})
     */
    public function index(AdsenseRepository $adsenseRepository): Response
    {
        return $this->render('adsense/index.html.twig', [
            'adsenses' => $adsenseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="adsense_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $adsense = new Adsense();
        $form = $this->createForm(AdsenseType::class, $adsense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adsense);
            $entityManager->flush();

            return $this->redirectToRoute('adsense_index');
        }

        return $this->render('adsense/new.html.twig', [
            'adsense' => $adsense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="adsense_show", methods={"GET"})
     */
    public function show(Adsense $adsense): Response
    {
        return $this->render('adsense/show.html.twig', [
            'adsense' => $adsense,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="adsense_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Adsense $adsense): Response
    {
        $form = $this->createForm(AdsenseType::class, $adsense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('adsense_index');
        }

        return $this->render('adsense/edit.html.twig', [
            'adsense' => $adsense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="adsense_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Adsense $adsense): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adsense->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adsense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adsense_index');
    }
}
