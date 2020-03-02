<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/12/5
 * Time: 21:53
 * Site: http://www.drupai.com
 */

namespace App\Menu;


use App\Entity\Menu;
use App\Entity\MenuItem;
use Knp\Menu\FactoryInterface;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder
{
    /**
     * @var ContainerInterface
     */
    private $container;

    private $factory;

    public function __construct(FactoryInterface $factory,ContainerInterface $container)
    {
        $this->factory = $factory;
        $this->container = $container;
    }

    public function mainMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(["class"=>"main-menu"]);
        $em = $this->container->get('doctrine')->getManager();
        $mains = $em->getRepository(MenuItem::class)->findBy(['menu'=>1]);

        foreach ($mains as $main)
        {
            $menu->addChild($main->getName(),[
                'route'=> $main->getLink()
            ]);
        }

        return $menu;
    }




}