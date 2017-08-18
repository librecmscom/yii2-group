<?php

namespace yuncms\group\models;

use Yii;
use yii\db\ActiveRecord;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%group_fans}}".
 *
 * @property int $group_id 群组ID
 * @property int $user_id 用户ID
 * @property string $payment_id 支付ID
 * @property string $fee 手续费
 * @property string $service_charge 服务费
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property Group $group
 * @property User $user
 */
class Fans extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_fans}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'user_id', 'status'], 'integer'],
            [['fee', 'service_charge'], 'number'],
            [['payment_id'], 'string', 'max' => 50],
            [['group_id', 'user_id'], 'unique', 'targetAttribute' => ['group_id', 'user_id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => Yii::t('group', '群组ID'),
            'user_id' => Yii::t('group', '用户ID'),
            'payment_id' => Yii::t('group', '支付ID'),
            'fee' => Yii::t('group', '手续费'),
            'service_charge' => Yii::t('group', '服务费'),
            'status' => Yii::t('group', '状态'),
            'created_at' => Yii::t('group', '创建时间'),
            'updated_at' => Yii::t('group', '更新时间'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return FansQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FansQuery(get_called_class());
    }
}
