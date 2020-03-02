<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/12/9
 * Time: 21:16
 * Site: http://www.drupai.com
 */

namespace App\Twig;





use App\Entity\Album;
use App\Entity\Friendship;
use App\Entity\Menu;
use App\Entity\Meta;
use App\Entity\Script;
use App\Entity\Settings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Entity\Tags;

class AppExtension  extends AbstractExtension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;



    public function __construct(ContainerInterface $container,UrlGeneratorInterface $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('sortBy',[$this,'sortBy'])
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction("getTags",[$this,"getTags"]),
            new TwigFunction("friendLinks",[$this,"friendLinks"]),
            new TwigFunction("hotAlbums",[$this,"hotAlbums"]),
            new TwigFunction("site",[$this,"site"]),
            new TwigFunction("getWeather",[$this,"getWeather"]),
            new TwigFunction("getBreadcrumb",[$this,"getBreadcrumb"]),
            new TwigFunction("getMeta",[$this,"getMeta"]),
            new TwigFunction("getScripts",[$this,"getScripts"]),
            new TwigFunction("logViewed",[$this,"logViewed"]),
            new TwigFunction("main_menu",[$this,"mainMenu"], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    public function mainMenu(Environment $twig)
    {
        $em = $this->getManager();
        $menu = $em->getRepository(Menu::class)->findOneBy(['name'=>'主菜单']);
        return $twig->render("default/menu_item.html.twig",[
            "mainMenu" => $menu,
        ]);
    }

    public function logViewed($id)
    {
        $em = $this->getManager();
        $album = $em->getRepository(Album::class)->find($id);
        $album->addViewed();
        $em->persist($album);
        $em->flush();
        return $album->getViewed();
    }

    public function sortBy($arr,$order,$property)
    {
        $getter = "get".ucfirst($property);

        if(strtolower($order) == "desc")
        {
            foreach ($arr as $index =>$ob)
            {
                $length = count($arr);
                for($i = 0; $i< $length - 1 - $index; $i++)
                {
                    $next = $i + 1;
                    if($arr[$i]->$getter() < $arr[$next]->$getter())
                    {
                        $tmp = $arr[$i];
                        $arr[$i] = $arr[$next];
                        $arr[$next] = $tmp;
                    }
                }

            }
        }
        return $arr;
    }

    public function getTags()
    {

        $em = $this->getManager();
        $num = mt_rand(5,20);
        $tags = $em->getRepository(Tags::class)->findBy([],[],$num);

        return $tags;
    }

    public function site()
    {
        $sites =  $this->getManager()->getRepository(Settings::class)->findAll();
        if(empty($sites))
        {
            return new Settings();
        }
        return end($sites);
    }

    public function friendLinks()
    {
        $em = $this->getManager();
        $links = $em->getRepository(Friendship::class)->findBy(['enable'=>true]);
        return $links;
    }

    public function hotAlbums($num)
    {
        $albums =  $this->getManager()->getRepository(Album::class)->findByViewed($num);

        return $albums;
    }

    public function getManager(): EntityManagerInterface
    {
        return $this->container->get("doctrine")->getManager();
    }

    public function getWeather(Request $request)
    {

        $ip = $request->getClientIp() == "127.0.0.1" ? "106.127.189.18":$request->getClientIp();
        $session = $request->getSession();
        $weather = $session->get("weather");

        if(null == $weather)
        {
            $url = "https://www.tianqiapi.com/api/?version=v6&appid=87196286&appsecret=28qqnYHV&ip=".$ip;
            $weather = file_get_contents($url);
            $session->set("weather",$weather);

        }

        return json_decode($weather);
    }

    public function getBreadcrumb($object)
    {
        $em = $this->getManager();
        $obClass = get_class($object);
        $obClass = substr($obClass,strrpos($obClass,"\\")+1);

        $breadcrumbs[] = $this->setBreadcrumb($object->getName());

        switch ($obClass)
        {
            case "Album":
                $category = $object->getCategory();
                $breadcrumbs[] = $this->setBreadcrumb($category->getName(),$this->router->generate("category_show",['id'=>$category->getId()]));

                $hasParent = $category->getParent() != null;
                while($hasParent)
                {
                    $parent = $category->getParent();
                    $breadcrumbs[] = $this->setBreadcrumb($parent->getName(),$this->router->generate("category_show",['id'=>$parent->getId()]));
                    $hasParent = $parent->getParent() != null;
                }
                break;
            case "Category":
                $hasParent = $object->getParent() != null;
                while($hasParent)
                {
                    $parent = $object->getParent();
                    $breadcrumbs[] = $this->setBreadcrumb($parent->getName(),$this->router->generate("category_show",['id'=>$parent->getId()]));
                    $hasParent = $parent->getParent() != null;
                }
                break;
            default:
                break;
        }
        $breadcrumbs[] = $this->setBreadcrumb("首页","/");

        return array_reverse($breadcrumbs);
    }

    public function setBreadcrumb($name=null,$link=null)
    {
        $breadcrumb['name'] = $name;
        $breadcrumb['link'] = $link;
        return $breadcrumb;
    }

    public function getMeta()
    {
        $em = $this->getManager();
        $metas = $em->getRepository(Meta::class)->findBy(["enable"=>true]);
        return $metas;
    }

    public function getScripts()
    {
        return $this->getManager()->getRepository(Script::class)->findBy(['enable'=>true]);
    }


}