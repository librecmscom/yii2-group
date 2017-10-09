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
 * This is the model class for table "{{%group_member}}".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $user_id
 * @property integer $role
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
class GroupMember extends ActiveRecord
{

    //场景定义
    const SCENARIO_CREATE = 'create';//创建
    const SCENARIO_UPDATE = 'update';//更新

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_member}}';
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
            [['group_id', 'user_id', 'role', 'status', 'created_at', 'updated_at', 'expired_at'], 'integer'],
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
            'id' => Yii::t('group', 'ID'),
            'group_id' => Yii::t('group', 'Group ID'),
            'user_id' => Yii::t('group', 'User ID'),
            'role' => Yii::t('group', 'Role'),
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
     * @return GroupMemberQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupMemberQuery(get_called_class());
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
     * 获取角色名称
     * @return string
     */
    public function getRoleName()
    {
        switch ($this->role) {
            case 0:
                $roleName = Yii::t('group', 'Owner');
                break;
            case 1:
                $roleName = Yii::t('group', 'Manage');
                break;
            case 2:
                $roleName = Yii::t('group', 'Guest');
                break;
            case 3:
                $roleName = Yii::t('group', 'Member');
                break;
            default:
                $roleName = Yii::t('group', 'Member');
        }
        return $roleName;
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
