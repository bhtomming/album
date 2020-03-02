<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Category;
use App\Entity\Star;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/album")
 */
class AlbumController extends AbstractController
{
    /**
     * @Route("/", name="album_index", methods={"GET"})
     */
    public function index(AlbumRepository $albumRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $mainCates = $em->getRepository(Category::class)->findBy(['parent'=>null]);

        $Stars = $em->getRepository(Star::class)->findBy([],[],20);

        return $this->render('album/index.html.twig', [
            'albums' => $albumRepository->findBy(['isPublished'=>true]),
            'mainCates' => $mainCates,
            'stars' =>$Stars,
        ]);
    }

    /**
     * @Route("/hua",name="mingxin")
     * @return Response
     */
    public function mingXing()
    {
        return $this->getByCategory("植物图片");
    }

    public function getByCategory($category)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['name'=>$category]);
        $albums = $this->getDoctrine()->getRepository(Album::class)->findBy(['category'=>$category]);
        return $this->render('album/index.html.twig',[
            'albums' => $albums,
        ]);
    }



    /**
     * @Route("/{id}", name="album_show", methods={"GET"})
     *@Route("/{id}", name="album_page", methods={"GET"},defaults={"page": "1"},requirements={"page": "[1-9]\d*"})
     */
    public function show(Request $request,Album $album): Response
    {
        $adapter = new DoctrineCollectionAdapter($album->getPictures());


        $page = $request->get('page')?:1;
        $pagefan = new Pagerfanta($adapter);
        $pagefan->setMaxPerPage(1);
        $pagefan->setCurrentPage($page);


        return $this->render('album/show.html.twig', [
            'album' => $album,
            'pics' =>$pagefan,
        ]);
    }
}
