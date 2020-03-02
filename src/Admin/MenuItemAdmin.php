<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/10/20
 * Time: 14:43
 * Site: http://www.drupai.com
 */

namespace App\Admin;



use App\Entity\Album;
use App\Entity\Menu;
use App\Entity\MenuItem;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class MenuItemAdmin extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $form)
    {
        $image = $this->getSubject();
        $fileFieldOptions['required'] = false;


        $form
            ->add('menu',EntityType::class,[
                'class' => Menu::class,
                'choice_label'=>'name',
                'choice_value'=>'id',
                'label' => '所属菜单'
            ])
            ->add('parent',EntityType::class,[
                'class' => MenuItem::class,
                'placeholder'=> '选择上级菜单',
                'choice_label'=>'name',
                'choice_value'=>'id',
                'required' => false,
                'label'=>'上级菜单'
            ])
            ->add('name',null,[
                'required'=>true,
                'label'=>'菜单名'
            ])
            ->add('link',null, [
                'label'=>'链接'
            ])
            ->add('mega',EntityType::class,[
                'class' => Album::class,
                'multiple' => true,
                'choice_label'=>'name',
                'choice_value'=>'id',
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

    public function preRemove($object)
    {
        $megas = $object->getMega();
        foreach ($megas as $mega)
        {
            $object->removeMega($mega);
        }
    }

    public function prePersist($object)
    {
        $this->saveMega($object);
    }

    public function preUpdate($object)
    {
        $this->saveMega($object);
    }

    public function saveMega($object)
    {
        $albums = $object->getMega();
        foreach ($albums as $album)
        {
            $album->setMenuItem($object);
        }

    }


}