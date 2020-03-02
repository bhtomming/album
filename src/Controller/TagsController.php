<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Star;
use App\Entity\Tags;
use App\Form\TagsType;
use App\Repository\TagsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tags")
 */
class TagsController extends AbstractController
{
    /**
     * @Route("/", name="tags_index", methods={"GET"})
     */
    public function index(TagsRepository $tagsRepository): Response
    {
        return $this->render('tags/index.html.twig', [
            'tags' => $tagsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="tags_show", methods={"GET"})
     */
    public function show(Tags $tag): Response
    {
        $stars = $this->getDoctrine()->getRepository(Star::class)->findBy([],[],[10]);
        return $this->render('tags/show.html.twig', [
            'tag' => $tag,
            'stars' => $stars,
        ]);
    }

}
