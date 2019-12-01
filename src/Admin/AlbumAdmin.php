<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/10/16
 * Time: 17:21
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use App\Entity\Category;
use App\Entity\Star;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\Form\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class AlbumAdmin extends AbstractAdmin
{

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('id',null,['label'=>'ID'])
            ->add("name",null,['label'=>'名称'])
            ->add('counterPictures',null,[
                'label' => '照片数',
            ])
        ;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form->add('name',null,['label'=>'相册名称'])
            ->add('keywords')
            ->add('description')
            ->add('category',ModelAutocompleteType::class,[
                'class' => Category::class,
                'placeholder'  => '选择相册分类',
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
            ->add('star',EntityType::class,[
                'class' => Star::class,
                'placeholder'=> '选择相册所属明星',
                'choice_label'=>function(Star $entity = null,$property){
                    return $entity ? $entity->getName() : '';
                },
                'choice_value' => function (Star $entity = null) {
                    return $entity ? $entity->getId() : '';
                },

            ])
            ->add('pictures',CollectionType::class,[
                //'sonata_admin' => PictureAdmin::class, //这个配置可有可无
                'sonata_admin' => 'sonata.admin.imageSpecial', //这个配置可有可无
                'type_options' => [
                    'delete' => true,
                    'btn_delete'=>true,
                ],

            ],
            [
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ])
        ;
    }

    public function prePersist($object)
    {
        $this->managerPictureAdmins($object);
    }

    public function preUpdate($object)
    {

        $this->managerPictureAdmins($object);
    }

    private function managerPictureAdmins($album)
    {
        $images = $album->getPictures();
        foreach ($images as $image)
        {
            if($image && $image->getFile() != null)
            {
                $image->setAlbum($album);
                $image->refreshUpdated();
            }else{
                if ($image->getName() == null)
                {
                    $album->removePicture($image);
                }
            }
        }
    }

}