<?php

namespace App\Controller;

use App\Entity\Meta;
use App\Form\MetaType;
use App\Repository\MetaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/meta")
 */
class MetaController extends AbstractController
{
    /**
     * @Route("/", name="meta_index", methods={"GET"})
     */
    public function index(MetaRepository $metaRepository): Response
    {
        return $this->render('meta/index.html.twig', [
            'metas' => $metaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="meta_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $metum = new Meta();
        $form = $this->createForm(MetaType::class, $metum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($metum);
            $entityManager->flush();

            return $this->redirectToRoute('meta_index');
        }

        return $this->render('meta/new.html.twig', [
            'metum' => $metum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="meta_show", methods={"GET"})
     */
    public function show(Meta $metum): Response
    {
        return $this->render('meta/show.html.twig', [
            'metum' => $metum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="meta_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Meta $metum): Response
    {
        $form = $this->createForm(MetaType::class, $metum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('meta_index');
        }

        return $this->render('meta/edit.html.twig', [
            'metum' => $metum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="meta_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Meta $metum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$metum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($metum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('meta_index');
    }
}
