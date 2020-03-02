<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/10/25
 * Time: 15:54
 * Site: http://www.drupai.com
 */

namespace App\Admin;



use App\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CategoryAdmin extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $form)
    {
        $form->add('parent',ModelAutocompleteType::class,[
            'class' => Category::class,
            'placeholder'  => '选择上级分类',
            'property' => 'id',
            'minimum_input_length' =>1,
            'callback'=>function($admin,$property,$value){
                $datagrid = $admin->getDatagrid();
                $queryBuilder = $datagrid->getQuery();
                $queryBuilder
                    ->andWhere($queryBuilder->getRootAlias() . '.name like :barValue')
                    ->setParameter('barValue','%'.$value.'%')
                ;
                $datagrid->setValue($property, null, $value);
            },
            'to_string_callback' => function(Category $entity = null,$property){
                return $entity ? $entity->getName() : '';
            }
        ])
            ->add('name',null,[
            'label'=>'名称'
        ])
            ->add('keywords',null,[
                'label'=>'关键词'
            ])
            ->add('description',null,[
                'label'=>'描述'
            ])
            ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name',null,[
            'label'=>'名称'
        ]);
    }

    public function preRemove($object)
    {
        $object->removeCategory();
    }

}