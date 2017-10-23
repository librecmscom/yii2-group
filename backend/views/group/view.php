<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;

/* @var $this yii\web\View */
/* @var $model yuncms\group\models\Group */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('group', 'Manage Group'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 group-view">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('group', 'Manage Group'),
                            'url' => ['index'],
                        ],
                        [
                            'label' => Yii::t('group', 'Create Group'),
                            'url' => ['create'],
                        ],
                        [
                            'label' => Yii::t('group', 'Update Group'),
                            'url' => ['update', 'id' => $model->id],
                            'options' => ['class' => 'btn btn-primary btn-sm']
                        ],
                        [
                            'label' => Yii::t('group', 'Delete Group'),
                            'url' => ['delete', 'id' => $model->id],
                            'options' => [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                                'id',
                    'user_id',
                    'name',
                    'logo',
                    'price',
                    'billing_cycle',
                    'days_free',
                    'introduce',
                    'allow_publish',
                    'applicants',
                    'status',
                    'blocked_at',
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>

