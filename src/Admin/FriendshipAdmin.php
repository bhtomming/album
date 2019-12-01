<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/10/27
 * Time: 14:03
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\BooleanType;

class FriendshipAdmin extends AbstractAdmin
{

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('siteName',null,[
                'label' => '网站名称',
            ])
            ->add('link',null,[
                'label' => '链接'
            ])
            ->add('enable','boolean',[
                'label' => '是否有效',
                'editable' => true,
            ])
            ;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form->add('siteName',null,[
            'label' => '网站名称'
            ])
            ->add('link',null,[
                'label' => '链接'
            ])
            ->add('enable',BooleanType::class,[
                'label' => '是否有效'
            ])
            ;
    }

}