<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/10/27
 * Time: 11:23
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use App\Entity\Settings;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SettingsAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('siteName');
    }

    protected function configureFormFields(FormMapper $form)
    {
        $logPath = Settings::SITE_IMAGES."/".$this->getSubject()->getLogo();
        $footerPath = Settings::SITE_IMAGES."/".$this->getSubject()->getFooterImg();
        $form
            ->add('siteName',null,[
            'label' => '网站名称'
            ])
            ->add("logoFile",FileType::class,[
                'label' => '网站LOGO',
                'required' => false,
                'help' => '<img src="/'.$logPath.'" class="admin-preview" style="max-width:100px;"/>'
            ])
            ->add('keywords',null,[
                'label' => '关键词'
            ])
            ->add('description',null,[
                'label' => '网站描述'
            ])
            ->add('summary',TextareaType::class,[
                'label'=>'网站概要',
                'required' => false,
            ])
            ->add('copyRight',null,[
                'label'=>'版权声明'
            ])
            ->add('email',EmailType::class,[
                'label'=>'邮箱'
            ])
            ->add("footerFile",FileType::class,[
                'label'=>'页脚图片',
                'required' => false,
                'help' => '<img src="/'.$footerPath.'" class="admin-preview" style="max-width:100px;" />'
            ])
            ->add('beian',null,[
                'label' => '备案信息'
            ])
            ->add('address',null,[
                'label' => '联系地址'
            ])
            ;
    }

    public function prePersist($object)
    {
        $object->upload();
    }

    public function preUpdate($object)
    {
        $object->upload();
    }

    public function managerFile($object)
    {
        if($object->getLogoFile() != null || $object->getFooterFile() != null)
        {
            $object->upload();
        }
    }

}