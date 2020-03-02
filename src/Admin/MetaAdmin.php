<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/10/27
 * Time: 14:40
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MetaAdmin extends AbstractAdmin
{

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('id',null)
            ->addIdentifier('name',null,[
                'label' => '名称'
            ])
            ->add('enable','boolean',[
                'label' => '是否有效',
                'editable' => true,
            ])
            ;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name', null, [
                'label' => '名称',
            ])
            ->add('codes', TextareaType::class, [
                'label' => '代码',
            ])
        ;

    }

}