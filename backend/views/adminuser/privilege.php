<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Adminuser;
/* @var $this yii\web\View */
/* @var $model common\models\Adminuser */

$this->title = '权限设置 ：'.Adminuser::findOne($id)->username;
$this->params['breadcrumbs'][] = ['label' => '权限列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adminuser-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>


   <?= Html::checkboxList('newPri',$userArr,$allArr)?>


    <div class="form-group">
        <?= Html::submitButton('设置', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
