<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/12/15
 * Time: 11:14
 * Site: http://www.drupai.com
 */

namespace App\Controller;



use App\Servers\Cat;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;

class TaskAdminController extends CRUDController
{

    public function startAction($id,KernelInterface $kernel,Cat $cat)
    {
        $task = $this->admin->getSubject();

        if(!$task)
        {
            throw new NotFoundHttpException(["没有找到内容"]);
        }

        $app = new Application($kernel);

        $app->setAutoExit(false);

        $input = new ArrayInput([
            'command'=>'app:cat',
            'arg1' => $task->getId(),
        ]);

        $output = new BufferedOutput();
        $app->run($input,$output);
       //$cat->setTask($task);
        //$cat->startTask();


        $this->addFlash('sonata_flash_success','操作成功'.$output->fetch());

        return new RedirectResponse($this->admin->generateUrl('list',['filter'=>$this->admin->getFilterParameters()]));
    }


}