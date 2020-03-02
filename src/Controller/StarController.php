<?php

namespace App\Controller;

use App\Entity\Star;
use App\Form\StarType;
use App\Repository\StarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/star")
 */
class StarController extends AbstractController
{
    /**
     * @Route("/", name="star_index", methods={"GET"})
     */
    public function index(StarRepository $starRepository): Response
    {
        return $this->render('star/index.html.twig', [
            'stars' => $starRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="star_show", methods={"GET"})
     */
    public function show(Star $star): Response
    {
        return $this->render('star/show.html.twig', [
            'star' => $star,
        ]);
    }


}
