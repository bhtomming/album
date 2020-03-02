<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     * @Route("/{id}", name="category_page", methods={"GET"},defaults={"page": "1"},requirements={"page": "[1-9]\d*"})
     */
    public function show(Request $request,Category $category): Response
    {
        $stars = [];
        $adapter = new DoctrineCollectionAdapter($category->getAlbums());

        $page = $request->get('page')?:1;
        $pagefan = new Pagerfanta($adapter);
        $pagefan->setMaxPerPage(10);
        $pagefan->setCurrentPage($page);

        foreach ($category->getAlbums() as $album)
        {
            $stars[] = $album->getStar();
        }
        $stars = array_values(array_unique(array_filter($stars)));
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'stars' => $stars,
            'albums' =>$pagefan,
        ]);
    }


}
