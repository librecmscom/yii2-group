<?php

namespace yuncms\group\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yuncms\system\helpers\DateHelper;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%group_order}}".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $user_id
 * @property string $payment_id
 * @property string $fee
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $expired_at
 *
 * @property Group $group
 * @property User $user
 *
 * @property-read bool isAuthor 是否是作者
 * @property-read boolean $isDraft 是否草稿
 * @property-read boolean $isPublished 是否发布
 */
class GroupOrder extends ActiveRecord
{

    //场景定义
    const SCENARIO_CREATE = 'create';//创建
    const SCENARIO_UPDATE = 'update';//更新

    //状态定义
    const STATUS_DRAFT = 0;//草稿
    const STATUS_REVIEW = 1;//审核
    const STATUS_REJECTED = 2;//拒绝
    const STATUS_PUBLISHED = 3;//发布


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_order}}';
    }

    /**
     * 定义行为
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
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
            static::SCENARIO_CREATE => [],
            static::SCENARIO_UPDATE => [],
        ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'user_id'], 'required', 'on' => [static::SCENARIO_CREATE, static::SCENARIO_UPDATE]],
            [['group_id', 'user_id'], 'integer', 'on' => [static::SCENARIO_CREATE, static::SCENARIO_UPDATE]],
            [['group_id', 'user_id'], 'unique', 'targetAttribute' => ['group_id', 'user_id'], 'on' => [static::SCENARIO_CREATE, static::SCENARIO_UPDATE]],
            [['fee'], 'number'],
            [['payment_id'], 'string', 'max' => 50],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id'],],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('group', 'ID'),
            'group_id' => Yii::t('group', 'Group ID'),
            'user_id' => Yii::t('group', 'User ID'),
            'payment_id' => Yii::t('group', 'Payment ID'),
            'fee' => Yii::t('group', 'Fee'),
            'status' => Yii::t('group', 'Status'),
            'created_at' => Yii::t('group', 'Created At'),
            'updated_at' => Yii::t('group', 'Updated At'),
            'expired_at' => Yii::t('group', 'Expired At'),
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
     * @return GroupOrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupOrderQuery(get_called_class());
    }

    /**
     * 获取状态列表
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_DRAFT => Yii::t('group', 'Draft'),
            self::STATUS_REVIEW => Yii::t('group', 'Review'),
            self::STATUS_REJECTED => Yii::t('group', 'Rejected'),
            self::STATUS_PUBLISHED => Yii::t('group', 'Published'),
        ];
    }

//    public function afterFind()
//    {
//        parent::afterFind();
//        // ...custom code here...
//    }

    /**
     * @inheritdoc
     */
//    public function beforeSave($insert)
//    {
//        if (!parent::beforeSave($insert)) {
//            return false;
//        }
//
//        // ...custom code here...
//        return true;
//    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert && $this->group->price == 0) {
            $member = new GroupMember([
                'role' => GroupMember::ROLE_MEMBER,
                'user_id' => $this->user_id,
            ]);
            $member->link('group', $this->group);

        }
        // ...custom code here...
    }

    /**
     * @inheritdoc
     */
//    public function beforeDelete()
//    {
//        if (!parent::beforeDelete()) {
//            return false;
//        }
//        // ...custom code here...
//        return true;
//    }

    /**
     * @inheritdoc
     */
//    public function afterDelete()
//    {
//        parent::afterDelete();
//
//        // ...custom code here...
//    }

    /**
     * 生成一个独一无二的标识
     */
    protected function generateSlug()
    {
        $result = sprintf("%u", crc32($this->id));
        $slug = '';
        while ($result > 0) {
            $s = $result % 62;
            if ($s > 35) {
                $s = chr($s + 61);
            } elseif ($s > 9 && $s <= 35) {
                $s = chr($s + 55);
            }
            $slug .= $s;
            $result = floor($result / 62);
        }
        //return date('YmdHis') . $slug;
        return $slug;
    }

    /**
     * 获取模型总数
     * @param null|int $duration 缓存时间
     * @return int get the model rows
     */
    public static function getTotal($duration = null)
    {
        $total = static::getDb()->cache(function ($db) {
            return static::find()->count();
        }, $duration);
        return $total;
    }

    /**
     * 获取模型今日新增总数
     * @param null|int $duration 缓存时间
     * @return int
     */
    public static function getTodayTotal($duration = null)
    {
        $total = static::getDb()->cache(function ($db) {
            return static::find()->where(['between', 'created_at', DateHelper::todayFirstSecond(), DateHelper::todayLastSecond()])->count();
        }, $duration);
        return $total;
    }
}
