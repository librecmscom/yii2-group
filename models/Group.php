<?php

namespace yuncms\group\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yuncms\question\models\Question;
use yuncms\system\helpers\DateHelper;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $logo
 * @property string $price
 * @property string $introduce
 * @property integer $allow_publish
 * @property integer $applicants
 * @property integer $status
 * @property integer $blocked_at
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property GroupMember[] $groupMembers
 * @property User[] $users
 * @property GroupOrder[] $groupOrders
 * @property User[] $users0
 * @property GroupTopic[] $groupTopics
 *
 * @property-read bool isAuthor 是否是作者
 * @property-read boolean $isDraft 是否草稿
 * @property-read boolean $isPublished 是否发布
 */
class Group extends ActiveRecord
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
        return '{{%group}}';
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
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            static::SCENARIO_CREATE => ['name'],
            static::SCENARIO_UPDATE => ['name'],
        ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['allow_publish', 'applicants', 'status'], 'integer'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['logo', 'introduce'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('group', 'ID'),
            'user_id' => Yii::t('group', 'User ID'),
            'name' => Yii::t('group', 'Name'),
            'logo' => Yii::t('group', 'Logo'),
            'price' => Yii::t('group', 'Price'),
            'introduce' => Yii::t('group', 'Introduce'),
            'allow_publish' => Yii::t('group', 'Allow Publish'),
            'applicants' => Yii::t('group', 'Applicants'),
            'status' => Yii::t('group', 'Status'),
            'blocked_at' => Yii::t('group', 'Blocked At'),
            'created_at' => Yii::t('group', 'Created At'),
            'updated_at' => Yii::t('group', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(GroupMember::className(), ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(GroupOrder::className(), ['group_id' => 'id']);
    }

    /**
     * 定义问题关系
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['id' => 'question_id'])->viaTable('{{%group_question}}', ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopics()
    {
        return $this->hasMany(GroupTopic::className(), ['group_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return GroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupQuery(get_called_class());
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
        if ($insert) {
            try {
                //将主播自己加入直播
                $join = new GroupMember([
                    'group_id' => $this->id,
                    'user_id' => $this->user_id,
                    'role' => GroupMember::ROLE_OWNER,
                ]);
                $join->link('group', $this);
                $this->updateCounters(['applicants' => 1]);
            } catch (Exception $e) {

            }
        }
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
}
