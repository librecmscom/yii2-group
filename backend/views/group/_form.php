<?php
use yii\helpers\Html;
use xutl\inspinia\ActiveForm;

/* @var \yii\web\View $this */
/* @var yuncms\group\models\Group $model */
/* @var ActiveForm $form */
?>
<?php $form = ActiveForm::begin(['layout'=>'horizontal', 'enableAjaxValidation' => true, 'enableClientValidation' => false,]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'logo')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'introduce')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'allow_publish')->textInput() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'applicants')->textInput() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'status')->textInput() ?>    <div class="hr-line-dashed"></div>


<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

