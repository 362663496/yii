<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20 0020
 * Time: 10:32
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;

class RbacController extends Controller
{
    public function actionUp()
    {
        $auth = Yii::$app->authManager;

        // 添加 "createPost" 权限
        $createPost = $auth->createPermission('createPost');
        $createPost->description = '新增文章';
        $auth->add($createPost);

        // 添加 "updatePost" 权限
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = '修改文章';
        $auth->add($updatePost);

        // 添加 "deletePost" 权限
        $deletePost = $auth->createPermission('deletePost');
        $deletePost->description = '删除文章';
        $auth->add($deletePost);

        // 添加 "approveComment" 权限
        $approveComment = $auth->createPermission('approveComment');
        $approveComment->description = '审核评论';
        $auth->add($approveComment);

        // 添加 "postadmin" 角色并赋予 "createPost" "updatePost" "deletePost"权限
        $postadmin = $auth->createRole('postAdmin');
        $postadmin->description = "文章管理员";
        $auth->add($postadmin);
        $auth->addChild($postadmin, $createPost);
        $auth->addChild($postadmin, $deletePost);
        $auth->addChild($postadmin, $updatePost);

        // 添加 "post0perator" 角色并赋予 "deletePost"
        $post0perator = $auth->createRole('post0perator');
        $post0perator->description = '文章操作员';
        $auth->add($post0perator);
        $auth->addChild($post0perator,$deletePost);

        // 添加 "commentAuditor" 角色并赋予 "approveComment"
        $commentAuditor = $auth->createRole('commentAuditor');
        $commentAuditor->description = '评论审核员';
        $auth->add($commentAuditor);
        $auth->addChild($commentAuditor,$approveComment);

        // 添加 "admin" 角色并赋予 "approveComment"
        $admin = $auth->createRole('admin');
        $admin->description = '系统管理员';
        $auth->add($admin);
        $auth->addChild($admin,$postadmin);
        $auth->addChild($admin,$commentAuditor);

        // 为用户指派角色。其中 1 和 2 是由 IdentityInterface::getId() 返回的id
        // 通常在你的 Adminser 模型中实现这个函数。
        $auth->assign($admin, 3);
        $auth->assign($postadmin, 1);
        $auth->assign($commentAuditor, 2);
        $auth->assign($post0perator, 4);
    }

    public function actionDown()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }
}