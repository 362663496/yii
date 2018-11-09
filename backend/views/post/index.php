<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Poststatus;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
<!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'title',
//            'content:ntext',
            'tags:ntext',

            [
                'attribute'=>'authorName',
                'label'=>'作者',
                'value'=>'author.nickname'
            ],

            [
                'attribute'=>'status',
                'value'=>'status0.name',
                'filter'=>Poststatus::find()
                    ->select('name')
                    ->orderBy('position')
                    ->indexBy('id')
                    ->column()
            ],

            //'create_time:datetime',
            [
                'attribute'=>'update_time',
                'format'=>['date','php:Y-m-d H:i:s']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
