<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/10/27
 * Time: 11:46
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use App\Entity\Navigation;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class NavigationAdmin extends AbstractAdmin
{

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name',null,[
            'label'=> '菜单名'
        ])
            ->add('keywords',null,[
                'label' => '关键词'
            ])
            ->add('description',null,[
                'label' => '描述'
            ])
            ->add('parent.name',EntityType::class,[
                'label' => '上级菜单',
            ])
            ;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name',null,[
                'label'=>'菜单名'
            ])
            ->add('keywords',null,[
                'label'=>'关键词'
            ])
            ->add('description',null,[
                'label' => '菜单描述'
            ])
            ->add('parent',EntityType::class,[
                'label' => '上级菜单',
                'class' => Navigation::class,
                'placeholder'=> '选择上级菜单',
                'choice_label'=>function(Navigation $entity = null,$property){
                    return $entity ? $entity->getName() : '';
                },
                'choice_value' => function (Navigation $entity = null) {
                    return $entity ? $entity->getId() : '';
                },
                'required' => false,
            ])
        ;
    }

}