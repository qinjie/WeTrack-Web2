<?php
namespace console\controllers;

use common\components\rbac\UserRoleRule;
use common\models\auth\CreatorRule;
use Yii;
use yii\console\Controller;

## Excecute the action on command line
## "php ..\..\yii rbac-demo/init"

class RbacDemoController extends Controller
{
    public function actionInit()
    {
        $this->actionClearAll();
        $this->actionCreateRoles();
        $this->actionAssignRoles();
        $this->actionCreateRules();

        $this->actionCreatePermsRules();
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

    public function actionCreateRules(){
        $auth = Yii::$app->authManager;
        # Add CreatorRule
        $rule_creator = new CreatorRule;
        $rule_creator->name = CreatorRule::name;
        $auth->add($rule_creator);

    }

    public function actionCreatePermsRules()
    {
        $this->actionCreatePermsRules_demo_test_post();

    }

    public function actionCreatePermsRules_demo_test_post()
    {
        $auth = Yii::$app->authManager;
        $manager = $auth->getRole('manager');
        $user = $auth->getRole('user');

        $name_create = 'demo/test-post/create';
        $name_update = 'demo/test-post/update';
        $name_view = 'demo/test-post/view';
        $name_index = 'demo/test-post/index';
        $name_delete = 'demo/test-post/delete';

        # test/Post for testing purpose
        $create = $auth->createPermission($name_create);
        $auth->add($create);
        $update = $auth->createPermission($name_update);
        $auth->add($update);
        $view = $auth->createPermission($name_view);
        $auth->add($view);
        $index = $auth->createPermission($name_index);
        $auth->add($index);
        $delete = $auth->createPermission($name_delete);
        $auth->add($delete);

        $auth->addChild($user, $view);
        $auth->addChild($user, $index);
        $auth->addChild($user, $create);
        $auth->addChild($manager, $update);
        $auth->addChild($manager, $delete);

        # Create permission to update own test-post
        $updateOwn = $auth->createPermission("{$name_update}_own");
        $updateOwn->description = "{$name_update}_own";
        $updateOwn->ruleName = CreatorRule::name;
        $auth->add($updateOwn);
        $auth->addChild($updateOwn, $update);
        $auth->addChild($user, $updateOwn);

        # Create permission to delete own test-post
        $deleteOwn = $auth->createPermission("{$name_delete}_own");
        $deleteOwn->description = "{$name_update}_own";
        $deleteOwn->ruleName = CreatorRule::name;
        $auth->add($deleteOwn);
        $auth->addChild($deleteOwn, $delete);
        $auth->addChild($user, $deleteOwn);
    }
}