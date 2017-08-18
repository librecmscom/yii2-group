<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model yuncms\group\models\Group */

$this->title = Yii::t('group', 'Create Group');
$this->params['breadcrumbs'][] = ['label' => Yii::t('group', 'Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
