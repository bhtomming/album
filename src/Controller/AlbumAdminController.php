<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/12/5
 * Time: 16:52
 * Site: http://www.drupai.com
 */

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController as BaseController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class AlbumAdminController extends BaseController
{
    public function batchActionPublish(ProxyQueryInterface $proxyQuery, Request $request = null)
    {
        $this->admin->checkAccess('edit');
        $this->admin->checkAccess('list');

        $modelManager = $this->admin->getModelManager();

        $selectIds = $request->get('idx');



        if(empty($selectIds))
        {
            $this->addFlash('sonata_flash_info','你没有选择要修改的信息');

            return new RedirectResponse($this->admin->generateUrl('list',[
                'filter' => $this->admin->getFilterParameters()
            ]));

        }
        $selectModels = $proxyQuery->execute();


        try{
            foreach ($selectModels as $selectModel)
            {
                if(!$selectModel->getIsPublished()){
                    $selectModel->setIsPublished(true);
                }
                //$selectModel->setViewed(random_int(100,99999));
                $modelManager->update($selectModel);
            }
        }catch (\Exception $e){
            $this->addFlash('sonata_flash_error','修改出错');

            return new RedirectResponse($this->admin->generateUrl('list',[
                'filter' => $this->admin->getFilterParameters()
            ]));
        }

        $this->addFlash('sonata_flash_success','发布相册成功');

        return new RedirectResponse($this->admin->generateUrl('list',[
                'filter' => $this->admin->getFilterParameters()
            ]));

    }

}