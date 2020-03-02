<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/10/20
 * Time: 14:43
 * Site: http://www.drupai.com
 */

namespace App\Admin;




use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType;


class MenuAdmin extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $form)
    {
        $image = $this->getSubject();
        $fileFieldOptions['required'] = false;


        $form
            ->add('name',null,[
                'required'=>true,
                'label'=>'菜单名'
            ])
            ->add('items',CollectionType::class,[
                //'sonata_admin' => PictureAdmin::class, //这个配置可有可无
                'sonata_admin' => MenuItemAdmin::class, //这个配置可有可无
                'type_options' => [
                    'delete' => true,
                    'btn_delete'=>true,
                ],
                'label'=>'菜单'

            ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'position',
                ])
        ;
    }

    protected function configureListFields(ListMapper $list)
    {

        $list->addIdentifier('name','html',[
            'label'=>'菜单名',
        ])
        ;
    }

    public function prePersist($object)
    {
        $items = $object->getItems();
        //dump($object);exit;
        foreach ($items as $item)
        {
            $item->setMenu($object);
        }
        //dump($object);exit;
    }

    public function preUpdate($object)
    {

    }


}