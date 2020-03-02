<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/12/14
 * Time: 17:47
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskAdmin extends AbstractAdmin
{

    public function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name',null,[
                'label'=>'任务名称'
            ])
            ->add('baseUrl',null,[
                'label'=>'目标基本链接'
            ])
            ->add('list',TextareaType::class,[
                'label'=>'内容列表规则',
                'required' => false,
            ])
            ->add('category',TextareaType::class,[
                'label'=>'分类规则',
                'required' => false,
            ])
            ->add('star',TextareaType::class,[
                'label'=>'名星规则',
                'required' => false,
            ])
            ->add('tag',TextareaType::class,[
                'label'=>'标签规则',
                'required' => false,
            ])
            ->add('page',TextareaType::class,[
                'label'=>'详情页面url规则',
            ])
            ->add('title',TextareaType::class,[
                'label'=>'内容标题规则',
                'required' => false,
            ])
            ->add('image',TextareaType::class,[
                'label'=>'图片规则',
                'required' => false,
            ])
            ->add('imgTitle',TextareaType::class,[
                'label'=>'图片标题规则',
                'required' => false,
            ])
            ->add('imgAlt',TextareaType::class,[
                'label'=>'图片说明规则',
                'required' => false,
            ])
            ->add('nav',TextareaType::class,[
                'label'=>'导航链接规则',
                'required' => false,
            ])
            ->add('pagination',TextareaType::class,[
                'label'=>'分页规则',
                'required' => false,
            ])
            ->add('breadcrumb',TextareaType::class,[
                'label'=>'面包屑规则',
                'required' => false,
            ])
        ;
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name',null,[
            'label'=>'任务名称'
        ])
            ->add('finished',BooleanType::class,[
                'label'=>'完成'
            ])
            ->add('createdAt',null,[
                'label' => '创建时间',
                'format'=>'Y年m月d日 H:i:s'
            ])
            ->add('finishedAt',null,[
                'label' => '完成时间',
                'format' => 'Y年m月d日 H:i:s'
            ])
            ->add('_action',null,[
                'actions' => [
                    'edit' =>[],
                    'start' => [
                        'template'=>'CRUD/list_action_start.html.twig'
                    ],
                ]
            ])
        ;
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('start',$this->getRouterIdParameter(),['start']);
    }



}