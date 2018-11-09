<?php
namespace console\controllers;

use yii\console\Controller;
use common\models\Post;
class HelloController extends Controller
{
    public $rev;
//    public function actionIndex()
//    {
//        echo "hello console\n";
//    }

    public function actionList()
    {
        $posts = Post::find()->all();
        foreach($posts as $k=>$v)
        {
            echo $k.". ".$v->title."\n";
        }
    }

    public function actionIndex()
    {
        if($this->rev==2)
        {
            echo strrev("Hello Yii")."\n";
        }
        else
        {
            echo "Hello Yii\n";
        }
    }

    public function options()
    {
        return ['rev'];
    }

    public function optionAliases()
    {
        return ['r'=>'rev'];
    }
}