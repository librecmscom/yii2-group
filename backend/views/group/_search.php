<?php

use yii\helpers\Html;
use xutl\inspinia\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yuncms\group\backend\models\GroupSearch */
/* @var $form ActiveForm */
?>

<div class="group-search pull-right">

    <?php $form = ActiveForm::begin([
        'layout' => 'inline',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'billing_cycle') ?>

    <?php // echo $form->field($model, 'days_free') ?>

    <?php // echo $form->field($model, 'introduce') ?>

    <?php // echo $form->field($model, 'allow_publish') ?>

    <?php // echo $form->field($model, 'applicants') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'blocked_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
