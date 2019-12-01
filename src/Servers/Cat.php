<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/10/27
 * Time: 17:09
 * Site: http://www.drupai.com
 */

namespace App\Servers;


use App\Entity\Album;
use App\Entity\Category;
use App\Entity\CrawlerCache;
use App\Entity\Picture;
use App\Entity\Star;
use App\Entity\Tags;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;

class Cat
{
    private $baseUrl;

    private $url;

    private $lists;

    private $categories;

    private $contents;

    private $client;

    private $crawler;

    private $em;

    private $container;

    private $task;

    private $isPagination;

    private $album;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->client = new Client();
        $this->crawler = new Crawler();
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $this->isPagination = false;
    }

    public function testTask($task)
    {
        $em = $this->getEm();
        $task = $em->getRepository(Task::class)->find($task);
        //echo var_export($task,true);
        $this->setTask($task);
    }

    public function setTask(Task $task)
    {
        $this->task = $task;
    }

    public function getTask() : ? Task
    {
        return $this->task;
    }

    public function startTask()
    {
        $navs = $this->getNav();

        if(!empty($navs))
        {
            foreach ($navs as $nav)
            {
                $this->setUrl($nav);
                $this->getPages();
            }
        }
        $em = $this->getEm();
        $cahes = $em->getRepository(CrawlerCache::class)->findBy(['task'=>$this->getTask()->getId(),'urlType'=>CrawlerCache::PAGE,'gather'=>false]);
       //echo var_export($pages);
        if(!empty($cahes))
        {
            foreach ($cahes as $cahe)
            {
                $this->readPage($cahe->getUrl());

                $paginations = $this->getPagination();
                if(!empty($paginations))
                {
                    $this->isPagination = true;
                    $paginations = array_unique($paginations);

                    foreach ($paginations as $pagination)
                    {
                        $this->readPage($pagination);
                        $this->isPagination = $pagination == end($paginations) ? false  : true;
                    }
                }

            }
        }

    }

    public function readPage($url)
    {
        $this->setUrl($url);
        $stars = $this->getStar();

        $categories = $this->getCategory();
        if(!$this->isPagination)
        {
            $titles = $this->getTitle();
            $tags = $this->getTags();
            $this->fixTags($tags);
            $star = $this->fixStars($stars);
            $category = $this->fixCategories($categories);
            $this->album = new Album();
            $this->album
                ->setName($titles[0])
                ->setCategory($category)
                ->setStar($star)
            ;
            echo "正在抓取文章：".$titles[0]."\n";

        }
        $files['name'] = $this->getImage();
        $files['alt'] = $this->getImageAlt();
        $files['title'] = $this->getImageTitle();

        $this->fixPictures($files);

    }

    public function fixPictures($files)
    {
        if(!empty($files['name']))
        {
            $em = $this->getEm();
            foreach ($files['name'] as $index => $name)
            {
                $picture = new Picture();
                $picture->setName($name);
                if(!empty($files['alt']))
                {
                    $picture->etAlt($files['alt'][$index]);
                }
                if(!empty($files['title']))
                {
                    $picture->setTitle($files['title'][$index]);
                }
                $this->album->addPicture($picture);
                //$em->persist($picture);
                $em->persist($this->album);
            }
            $em->flush();
        }
    }

    public function fixStars($names)
    {
        $star = null;
        if(!empty($names))
        {
            $em = $this->getEm();
            foreach ($names as $name)
            {
                $rs = $em->getRepository(Star::class)->findBy(['name'=>$name]);
                if(empty($rs))
                {
                    $star = new Star();
                    $star->setName($name);
                    $em->persist($star);
                }else{
                    return $rs[0];
                }

            }
            $em->flush();
        }
        return $star;
    }

    public function fixCategories($categories)
    {
        $cate = null;
        if(!empty($categories))
        {
            $em = $this->getEm();
            foreach ($categories as $category)
            {
                $rs = $em->getRepository(Category::class)->findBy(['name'=>$category]);
                if(empty($rs))
                {
                    $cate = new Category();
                    $cate->setName($category);
                    $em->persist($cate);
                }else{
                    return $rs[0];
                }
            }
            $em->flush();
        }
        return $cate;
    }

    public function fixTags($names)
    {
        if(!empty($names))
        {
            $em = $this->getEm();
            foreach ($names as $name)
            {
                $rs = $em->getRepository(Tags::class)->findBy(['name'=>$name]);
                if(empty($rs))
                {
                    $tag = new Tags();
                    $tag->setName($name);
                    $em->persist($tag);
                }
            }
            $em->flush();
        }
    }

    public function getNav($regular = null)
    {
        if($regular == null && $this->task)
        {
            $regular = $this->getTask()->getNav();

        }
        $navs = $this->toLink($regular,'href');

        $this->saveCahe($navs,CrawlerCache::NAV);

        return $navs;
    }

    public function getPagination($regular = null)
    {
        if($regular == null && $this->task)
        {
            $regular = $this->getTask()->getPagination();
        }
        $paginations = $this->toLink($regular,'href');

        return $paginations;
    }


    public function getList($regular = null)
    {
        if($regular == null && $this->task)
        {
            $regular = $this->getTask()->getList();
        }
        $this->lists = $this->toLink($regular,'href');

        $this->saveCahe($this->lists,CrawlerCache::LIST);

        return $this->lists;
    }

    public function getCategory($regular = null)
    {
        if($regular == null && $this->task)
        {
            $regular = $this->getTask()->getCategory();
        }
        $this->categories = $this->toLink($regular);

        //$this->updateCache();
        //$this->saveCahe($this->categories,CrawlerCache::CATEGORY);

        return $this->categories;
    }

    public function getTitle($regular = null)
    {
        if($regular == null && $this->task)
        {
            $regular = $this->getTask()->getTitle();
        }
        $title = $this->toLink($regular);

        //$this->updateCache();
        //$this->saveCahe($this->categories,CrawlerCache::CATEGORY);

        return $title;
    }

    public function getImageAlt($regular = null)
    {
        if($regular == null && $this->task)
        {
            $regular = $this->getTask()->getImgAlt();
            if($regular == null)
            {
                return [];
            }
        }
        $alts = $this->toLink($regular,'alt');

        $this->updateCache();

        return $alts;
    }

    public function getImageTitle($regular = null)
    {
        if($regular == null && $this->task)
        {
            $regular = $this->getTask()->getImgTitle();
            if($regular == null)
            {
                return [];
            }
        }
        $title = $this->toLink($regular,'title');
        $this->updateCache();
        return $title;
    }

    public function getStar($regular = null)
    {
        if($regular == null && $this->task)
        {
            $regular = $this->getTask()->getStar();
            if($regular == null)
            {
                return [];
            }
        }
        $stars = $this->toLink($regular);

        $em = $this->getEm();

        foreach ($stars as $star)
        {

            $rstars = $em->getRepository(Star::class)->findBy(['name'=>$star]);

            if(!empty($rstars)){
                continue;
            }
            $nStar = new Star();
            $nStar->setName($star);
            $em->persist($nStar);
        }
        $em->flush();

        return $stars;
    }

    public function getTags($regular = null)
    {
        if($regular == null && $this->task)
        {
            $regular = $this->getTask()->getTag();
            if($regular == null)
            {
                return [];
            }
        }
        $tags = $this->toLink($regular);
        //$this->updateCache();

        $em = $this->getEm();

        foreach ($tags as $tag)
        {
            $rstars = $em->getRepository(Star::class)->findBy(['name'=>$tag]);
            if(!empty($rstars)){
                continue;
            }
            $nTag = new Tags();
            $nTag->setName($tag);
            $em->persist($nTag);
        }
        $em->flush();

        return $tags;
    }

    public function getImage($regular = null)
    {
        if($regular == null && $this->task)
        {
            $regular = $this->getTask()->getImage();
        }
        $imgs = $this->toLink($regular,'src');
        //$imgs = $this->toLink($regular,'data-original');
        //$this->updateCache();

        $fileSystem = new Filesystem();
        $name = time();
        $path = "public/".Picture::SERVER_PATH_TO_IMAGE_FOLDER.date('Y',$name)."/".date('m',$name)."/".date('d',$name)."/";
        $fileNames = [];
        foreach ($imgs as $index => $img)
        {
            $exten = substr($img,strrpos($img,"."));
            $fileNames[] = $name."_".$index.$exten;
            $fileSystem->copy($img,$path.$fileNames[$index]);
        }
        return $fileNames;
    }

    public function getContentPage($regular = null)
    {
        if($regular == null && $this->task)
        {
            $regular = $this->getTask()->getPage();
        }

        $Pages = $this->toLink($regular,'href');

        $this->saveCahe($Pages,CrawlerCache::CONTENT);
        $this->updateCache();
        return $Pages;
    }

    public function getPages($regular = null)
    {
        if($regular == null && $this->task)
        {
            $regular = $this->getTask()->getList();
        }
        $Pages = $this->toLink($regular,'href');

        $this->saveCahe($Pages,CrawlerCache::PAGE);
        //$this->updateCache();
        return $Pages;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        $this->crawler = $this->client->request("GET",$url);
        preg_match('/[\w][\w-]*\.(?:com\.cn|com|cn|co|net|org|gov|cc|biz|info|me)(\/|$)/isU', $url, $domain);
        $this->baseUrl = substr($this->url,0,strpos($this->url,":") + 1)."//".rtrim($domain[0],"/");
    }

    public function setLists($lists)
    {
        $this->lists = $lists;
    }

    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    public function toLink($regular,$attr = null)
    {
        $datails = $this->crawler->filterXPath($regular)->each(function($node,$i) use($attr){
            if($attr == null){
                return $node->text();
            }
            $src = $node->attr($attr);
            if(empty($src)){
                return "找不到内容";
            }
            if(strpos($src,"http") === 0){
                return $src;
            }
            if(strpos($src,"//") === 0){
                $src = substr($this->url,0,strpos($this->url,":") + 1).$src;
            }else if(strpos($src,"/" )=== 0){
                $src = $this->baseUrl.$src;
            }else{
                $src = $this->url.$src;
            }
            return $src;
        });

        return $datails;
    }

    private function saveCahe($urls,$type)
    {
        $taskId = $this->getTask()->getId();

        $cachesRecords = $this->getEm()->getRepository(CrawlerCache::class)->findBy(['task'=>$taskId,'urlType'=>$type]);

        $urlRecords = [];

        if(!empty($cachesRecords))
        {
            foreach ($cachesRecords as $cachesRecord)
            {
                $urlRecords[] = $cachesRecord->getUrl();
            }
        }

        foreach ($urls as $url)
        {
            if(!in_array($url,$urlRecords)){
                $cache = new CrawlerCache();
                $cache->setUrl($url)
                    ->setTask($taskId)
                    ->setUrlType($type);
                $this->em->persist($cache);
            }
        }
        $this->em->flush();
    }

    private function delCahe($id)
    {
        $em = $this->getEm();
        $query = $em->createQuery(
            'DELETE 
            FROM App\Entity\CrawlerCache c
            WHERE c.task = :task_id'
        )->setParameter('task_id', $id);

        // returns an array of Product objects
        return $query->getResult();
    }

    private function updateCache()
    {
        $em = $this->getEm();
        $caches = $em->getRepository(CrawlerCache::class)->findBy(['url',$this->url]);

        if(!empty($caches)){
            foreach ($caches as $cache)
            {
                $cache->setGather(true);
            }
            $em->flush();
        }

    }

    public function getEm() :EntityManagerInterface
    {
        return $this->em;
    }




}