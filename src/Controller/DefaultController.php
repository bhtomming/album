<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/12/14
 * Time: 14:53
 * Site: http://www.drupai.com
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="page_index", methods={"GET"})
     */
    public function index()
    {
        return $this->redirectToRoute("album_index");
    }

}