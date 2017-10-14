<?php

namespace yuncms\group\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yuncms\system\helpers\DateHelper;
use yuncms\system\ScanInterface;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%group_topic}}".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $user_id
 * @property integer $model_id
 * @property string $model
 * @property string $subject
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Group $group
 * @property User $user
 *
 * @property-read bool isAuthor 是否是作者
 * @property-read boolean $isDraft 是否草稿
 * @property-read boolean $isPublished 是否发布
 */
class GroupTopic extends ActiveRecord implements ScanInterface
{

    //场景定义
    const SCENARIO_CREATE = 'create';//创建
    const SCENARIO_UPDATE = 'update';//更新

    //状态定义
    const STATUS_DRAFT = 0b0;//草稿
    const STATUS_REVIEW = 0b1;//待审核
    const STATUS_REJECTED = 0b10;//拒绝
    const STATUS_PUBLISHED = 0b11;//发布

    //事件定义
    const BEFORE_PUBLISHED = 'beforePublished';
    const AFTER_PUBLISHED = 'afterPublished';
    const BEFORE_REJECTED = 'beforeRejected';
    const AFTER_REJECTED = 'afterRejected';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_topic}}';
    }

    /**
     * 定义行为
     */
    public function behaviors()
    {
        return parent::behaviors();
//        return [
//            [
//                'class' => TimestampBehavior::className(),
//            ],
//            [
//                'class' => BlameableBehavior::className(),
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
//                ],
//            ]
//        ];
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
            [['group_id', 'user_id', 'model_id'], 'integer'],
            [['model_id', 'model'], 'required'],
            [['model', 'subject'], 'string', 'max' => 255],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],

            ['status', 'default', 'value' => self::STATUS_REVIEW],
            ['status', 'in', 'range' => [self::STATUS_DRAFT, self::STATUS_REVIEW, self::STATUS_REJECTED, self::STATUS_PUBLISHED]],
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
            'model_id' => Yii::t('group', 'Model ID'),
            'model' => Yii::t('group', 'Model'),
            'subject' => Yii::t('group', 'Subject'),
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
     * @return GroupTopicQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupTopicQuery(get_called_class());
    }

    /**
     * 是否是作者
     * @return bool
     */
    public function getIsAuthor()
    {
        return $this->user_id == Yii::$app->user->id;
    }

    /**
     * 是否草稿状态
     * @return bool
     */
    public function isDraft()
    {
        return $this->status == static::STATUS_DRAFT;
    }

    /**
     * 是否发布状态
     * @return bool
     */
    public function isPublished()
    {
        return $this->status == static::STATUS_PUBLISHED;
    }

    /**
     * 机器审核
     * @param int $id model id
     * @param string $suggestion the ID to be looked for
     * @return void
     */
    public static function review($id, $suggestion)
    {
        if (($model = static::findOne($id)) != null) {
            if ($suggestion == 'pass') {
                $model->setPublished();
            } elseif ($suggestion == 'block') {
                $model->setRejected('');
            } elseif ($suggestion == 'review') { //人工审核，不做处理

            }
        }
    }

    /**
     * 获取待审
     * @param int $id
     * @return string 待审核的内容字符串
     */
    public static function findReview($id)
    {
        if (($model = static::findOne($id)) != null) {
            return $model->content;
        }
        return null;
    }

    /**
     * 审核通过
     * @return int
     */
    public function setPublished()
    {
        $this->trigger(self::BEFORE_PUBLISHED);
        $rows = $this->updateAttributes(['status' => static::STATUS_PUBLISHED, 'published_at' => time()]);
        $this->trigger(self::AFTER_PUBLISHED);
        return $rows;
    }

    /**
     * 拒绝通过
     * @param string $failedReason 拒绝原因
     * @return int
     */
    public function setRejected($failedReason)
    {
        $this->trigger(self::BEFORE_REJECTED);
        $rows = $this->updateAttributes(['status' => static::STATUS_REJECTED, 'failed_reason' => $failedReason]);
        $this->trigger(self::AFTER_REJECTED);
        return $rows;
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
//    public function afterSave($insert, $changedAttributes)
//    {
//        parent::afterSave($insert, $changedAttributes);
//        Yii::$app->queue->push(new ScanTextJob([
//            'modelId' => $this->getPrimaryKey(),
//            'modelClass' => get_class($this),
//            'scenario' => $this->isNewRecord ? 'new' : 'edit',
//            'category'=>'',
//        ]));
//        // ...custom code here...
//    }

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
