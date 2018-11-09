<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '文章', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'content:ntext',
            'tags:ntext',
//            'status',
            [
                'attribute'=>'status',
                'value'=>$model->status0->name
            ],
            [
                'attribute'=>'create_time',
                'value'=>date('Y-m-d H:i:s',$model->create_time)
            ],
            [
                'attribute'=>'update_time',
                'value'=>date('Y-m-d H:i:s',$model->update_time)
            ],
//            'create_time:datetime',
//            'update_time:datetime',
            [
                'attribute'=>'author_id',
                'value'=>$model->author->nickname
            ],
        ],
        'template'=>"<tr><th style='width:120px;'>{label}</th><td>{value}</td></tr>",
//        'options'=>["class"=>'table']
    ]) ?>

</div>
