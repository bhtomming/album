<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/10/27
 * Time: 11:23
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class SettingsAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('siteName');
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form->add('siteName',null,[
            'label' => '网站名称'
        ])
            ->add('keywords',null,[
                'label' => '关键词'
            ])
            ->add('description',null,[
                'label' => '网站描述'
            ])
            ->add('copyRight',null,[
                'label'=>'版权声明'
            ])
            ->add('email',EmailType::class,[
                'label'=>'邮箱'
            ])
            ->add('beian',null,[
                'label' => '备案信息'
            ])
            ->add('address',null,[
                'label' => '联系地址'
            ])
            ;
    }

}