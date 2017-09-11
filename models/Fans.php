<?php

namespace yuncms\group\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%group_fans}}".
 *
 * @property int $group_id 群组ID
 * @property int $user_id 用户ID
 * @property string $payment_id 支付ID
 * @property string $fee 手续费
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property Group $group
 * @property User $user
 */
class Fans extends ActiveRecord
{
    // 等待中
    const STATUS_PENDING = 0;

    const STATUS_ACTIVE = 1;

    const SCENARIO_CREATE = 'create';

    const SCENARIO_UPDATE = 'update';

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
            [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            'create' => ['group_id'],
            'update' => ['group_id'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id'], 'integer'],
            [['fee'], 'number'],
            [['payment_id'], 'string', 'max' => 50],
            [
                ['group_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Group::className(),
                'targetAttribute' => ['group_id' => 'id']
            ],

            [
                'group_id',
                function ($attribute) {
                    if (Fans::find()->where([
                        'user_id' => Yii::$app->user->id,
                        'group_id' => $this->group_id
                    ])->exists()) {
                        $this->addError($attribute, Yii::t('app', 'Already exists.'));
                    }
                }
            ],

            // status rules
            ['status', 'default', 'value' => self::STATUS_PENDING],
            ['status', 'in', 'range' => [self::STATUS_PENDING, self::STATUS_ACTIVE,]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => Yii::t('group', 'Group Id'),
            'user_id' => Yii::t('group', 'User Id'),
            'payment_id' => Yii::t('group', 'Payment Id'),
            'fee' => Yii::t('group', 'Fee'),
            'status' => Yii::t('group', 'Status'),
            'created_at' => Yii::t('group', 'Created At'),
            'updated_at' => Yii::t('group', 'Updated At'),
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

    /**
     * 保存前执行
     * @param bool $insert 是否是插入操作
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert) {
            if ($this->group->isAuthor) {
                $this->status = self::STATUS_ACTIVE;
            } else {
                if ($this->group->price == 0) {
                    $this->status = self::STATUS_ACTIVE;
                } else {
                    $this->status = self::STATUS_PENDING;
                }
            }
            //计算服务费
            $this->fee = bcmul($this->group->price, bcdiv(Yii::$app->params['group.fee'], 100, 2), 2);//计算手续费保留两位小数
        }
        return true;
    }

    /**
     * 保存后执行
     * @param bool $insert 是否是插入操作
     * @param array $changedAttributes 更改的属性
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->group->link('fans', $this);
        }
        parent::afterSave($insert, $changedAttributes);
    }
}
