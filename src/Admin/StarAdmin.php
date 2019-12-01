<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/10/25
 * Time: 15:42
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class StarAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('name',null,[
            'label'=>'名字'
        ])
            ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name',null,[
            'label'=>'名字'
        ]);
    }

}