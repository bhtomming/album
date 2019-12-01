<?php

namespace App\Command;

use App\Entity\CrawlerCache;
use App\Servers\Cat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CatCommand extends Command
{
    protected static $defaultName = 'app:cat';

    private $cat;

    public function __construct(Cat $cat,$name = null)
    {
        parent::__construct($name);
        $this->cat = $cat;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('list', null, InputOption::VALUE_NONE, 'Option description')
            ->addArgument('regular',InputArgument::OPTIONAL,'Input a regular')
            ->addOption('category', null, InputOption::VALUE_NONE, 'Category Option description')
            ->addOption('alt', null, InputOption::VALUE_NONE, 'ImageAlt Option description')
            ->addOption('tag', null, InputOption::VALUE_NONE, 'ImageTag Option description')
            ->addOption('image', null, InputOption::VALUE_NONE, 'Image Option description')
            ->addOption('nav', null, InputOption::VALUE_NONE, 'Image Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $regular = $input->getArgument('regular');

        $cat = $this->cat;
        $cat->setUrl($arg1);

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if($regular == 1){
            $cat->testTask(1);
            $cat->startTask();
            return 0;
        }

        if ($input->getOption('nav')) {
            $io->note('您下在获取网站导航:');
            //$lists = $cat->getList($regular);
            $navs = $cat->getNav($regular);
            $io->note($navs);
        }


        if ($input->getOption('list')) {
            $io->note('您下在获取列表:');
            //$lists = $cat->getList($regular);
            $lists = $cat->getListPage($regular);
            $io->note($lists);
        }

        if($input->getOption('category')){
            $io->note('你要获取分类：');
            $categories = $cat->getCategory($regular);
            $io->note($categories);
        }

        if($input->getOption('alt')){
            $io->note('你要获取alt：');
            $alt = $cat->getImageAlt($regular);
            $io->note($alt);
        }

        if($input->getOption('tag')){
            $io->note('你要获取标签：');
            $alt = $cat->getTags($regular);
            $io->note($alt);
        }

        if($input->getOption('image')){
            $io->note('你要获取图片：');
            $imgs = $cat->getImage($regular);
            $io->note($imgs);
        }

        $io->success('run it success!');

        return 0;
    }


}
