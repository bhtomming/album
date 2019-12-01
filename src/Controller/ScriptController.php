<?php

namespace App\Controller;

use App\Entity\Script;
use App\Form\ScriptType;
use App\Repository\ScriptRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/script")
 */
class ScriptController extends AbstractController
{
    /**
     * @Route("/", name="script_index", methods={"GET"})
     */
    public function index(ScriptRepository $scriptRepository): Response
    {
        return $this->render('script/index.html.twig', [
            'scripts' => $scriptRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="script_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $script = new Script();
        $form = $this->createForm(ScriptType::class, $script);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($script);
            $entityManager->flush();

            return $this->redirectToRoute('script_index');
        }

        return $this->render('script/new.html.twig', [
            'script' => $script,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="script_show", methods={"GET"})
     */
    public function show(Script $script): Response
    {
        return $this->render('script/show.html.twig', [
            'script' => $script,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="script_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Script $script): Response
    {
        $form = $this->createForm(ScriptType::class, $script);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('script_index');
        }

        return $this->render('script/edit.html.twig', [
            'script' => $script,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="script_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Script $script): Response
    {
        if ($this->isCsrfTokenValid('delete'.$script->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($script);
            $entityManager->flush();
        }

        return $this->redirectToRoute('script_index');
    }
}
