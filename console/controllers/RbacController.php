<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

## Excecute the action on command line
## "php ..\..\yii rbac/init"

class RbacController extends Controller
{
    public function actionInit()
    {
        $this->actionClearAll();
        $this->actionCreateRoles();
        $this->actionAssignRoles();
    }

    public function actionClearAll()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }

    public function actionCreateRoles()
    {
        $auth = Yii::$app->authManager;
        # Create Roles
        $user = $auth->createRole('user');
        $auth->add($user);
        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $master = $auth->createRole('master');
        $auth->add($master);

        $auth->addChild($manager, $user);
        $auth->addChild($admin, $manager);
        $auth->addChild($master, $admin);
    }

    public function actionAssignRoles()
    {
        $auth = Yii::$app->authManager;

        $master = $auth->getRole('master');
        $admin = $auth->getRole('admin');
        $manager = $auth->getRole('manager');
        $user = $auth->getRole('user');

        $auth->assign($master, 1);
        $auth->assign($admin, 2);
        $auth->assign($manager, 3);
        $auth->assign($manager, 4);
        $auth->assign($user, 5);
        $auth->assign($user, 6);
        $auth->assign($user, 7);
        $auth->assign($user, 8);
    }

}