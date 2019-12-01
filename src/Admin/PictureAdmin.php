<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/10/20
 * Time: 14:43
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use App\Entity\Picture;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PictureAdmin extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $form)
    {
        $image = $this->getSubject();
        $fileFieldOptions['required'] = false;
        $fileFieldOptions['label'] = '照片';
        if ($image && ($webPath = $image->getWebPath())) {
            // get the container so the full path to the image can be set
           /* $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath().'/'.$webPath;*/

            // add a 'help' option containing the preview's img tag
            $fileFieldOptions['help'] = '<img src="/'.$webPath.'" class="admin-preview"/>';
        }

        $form
            ->add('name',null,[
                'required'=>false,
                'label'=>'文件名'
            ])
            ->add('file',FileType::class,
            $fileFieldOptions)
            ->add('title',null,[
                'label'=>'标题'
            ])
            ->add('description',null,[
                'label'=>'描述'
            ])

        ;
    }

    protected function configureListFields(ListMapper $list)
    {

        $list->addIdentifier('review','html',[
            'label'=>'照片预览',
            'header_style'=>"width: 100px; height: 100px;"
        ])
            ->add('name',null,[
            'label' => '图片名',
        ])
            ->add('title',null,[
                'label' => '图片标题',
            ])
            ->add('keywords',null,[
                'label' => '关键词',
            ])
            ->add('description',null,[
                'label' => '图片描述'
            ])
            ->add('createdAt',null,[
                'label' => '上传时间',
                'format'=>'Y年m月d日 H:i:s',
                'timezone' => 'Asia/Shanghai',
            ])
        ;

    }

    public function prePersist($object)
    {
        $this->managerFile($object);
    }

    public function preUpdate($object)
    {
        $this->managerFile($object);
    }

    private function managerFile(Picture $image)
    {
        if($image->getFile())
        {
            $image->refreshUpdated();
        }
    }

}