<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Commentstatus;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评论管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

  

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            [
                'attribute'=>'content' ,
                'value'=>"substring"
            ],
//            'status',
            [
                'attribute'=>'status' ,
                'value'=>"status0.name",
                'filter'=>Commentstatus::find()
                        ->select('name')
                        ->orderBy('position')
                        ->indexBy('id')
                        ->column(),
                'contentOptions' => function($model){
                    return $model->status==1?['class'=>'bg-danger']:[];
                }
            ],
//            'create_time:datetime',
            [
                'attribute'=>'create_time' ,
                'format'=>['date','php:Y-m-d H:i:s']
            ],
//            'userid',
            [
                'attribute'=>'user.username' ,
                'value'=>"user.username",
                'label'=>'用户'
            ],
            //'email:email',
            //'url:url',
            [
                'attribute'=>'post_title' ,
                'value'=>"post.title",
                'label'=>'文章',
            ],
            //'remind',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>"{view} {update} {delete} {approve}",
                'buttons'=>[
                    'approve'=>function($url,$model,$key)
                        {
                           $options = [
                               "title"=>Yii::t('yii','审核'),
                               "aria-label"=>Yii::t('yii','审核'),
                               "data-confirm"=>Yii::t('yii','您确定通过这条审核吗'),
                               "data-method"=>'post',
                               "data-pjax"=>'0',
                           ];
                            return Html::a("<span class='glyphicon glyphicon-check'></span>",$url,$options);
                        }
                ],
            ],
        ],
    ]); ?>
</div>
