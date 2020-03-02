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
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class MenuItemAdmin extends AbstractAdmin
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
            ->add('link',null, [
                'label'=>'链接'
            ])
            ->add('mega',CollectionType::class,[
                //'sonata_admin' => PictureAdmin::class, //这个配置可有可无
                'sonata_admin' => AlbumAdmin::class, //这个配置可有可无
                'type_options' => [
                    'delete' => true,
                    'btn_delete'=>true,
                ],
                'label'=>'相册'

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
            ->add('link',null,[
                'label' => '链接',
            ])
        ;

    }



}