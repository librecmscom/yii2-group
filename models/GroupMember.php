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
 * @property-read string $roleName 角色名称
 */
class GroupMember extends ActiveRecord
{

    //场景定义
    const SCENARIO_CREATE = 'create';//创建
    const SCENARIO_UPDATE = 'update';//更新

    const ROLE_OWNER = 0b0;
    const ROLE_MANAGE = 0b1;
    const ROLE_GUEST = 0b10;
    const ROLE_MEMBER = 0b11;

    const STATUS_NORMAL = 0b0;//正常
    const STATUS_BAN = 0b1;//禁言

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
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            static::SCENARIO_CREATE => ['group_id', 'user_id'],
            static::SCENARIO_UPDATE => ['group_id', 'user_id'],
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

            ['role', 'default', 'value' => self::ROLE_MEMBER, 'on' => [static::SCENARIO_CREATE]],
            ['role', 'in', 'range' => [self::ROLE_OWNER, self::ROLE_MANAGE, self::ROLE_GUEST, self::ROLE_MEMBER]],

            ['status', 'default', 'value' => self::STATUS_NORMAL, 'on' => [static::SCENARIO_CREATE]],
            ['status', 'in', 'range' => [self::STATUS_NORMAL, self::STATUS_BAN]],

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
     * 获取角色名称
     * @return string
     */
    public function getRoleName()
    {
        switch ($this->role) {
            case 0b0:
                $roleName = Yii::t('group', 'Owner');
                break;
            case 0b1:
                $roleName = Yii::t('group', 'Manage');
                break;
            case 0b10:
                $roleName = Yii::t('group', 'Guest');
                break;
            case 0b11:
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
}
