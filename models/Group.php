<?php

namespace yuncms\group\models;

use Yii;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property int $id 主键
 * @property string $name 群组名字
 * @property string $logo 群组logo
 * @property int $user_id 创建者ID
 * @property string $price 加入价格
 * @property string $introduce 群组介绍
 * @property int $allow_publish 是否允许发布内容
 * @property int $applicants 加入人数
 * @property int $status 状态
 * @property int $blocked_at 群组锁定时间
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property User $user
 * @property Fans[] $fans
 * @property User[] $users
 * @property Topic[] $topics
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group}}';
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
            [['name', 'logo', 'user_id'], 'required'],
            [['user_id', 'allow_publish', 'applicants', 'status', 'blocked_at'], 'integer'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['logo', 'introduce'], 'string', 'max' => 255],
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
            'name' => Yii::t('group', 'Group Name'),
            'logo' => Yii::t('group', 'logo'),
            'user_id' => Yii::t('group', 'User ID'),
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
    public function getFans()
    {
        return $this->hasMany(Fans::className(), ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('{{%group_fans}}', ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupTopics()
    {
        return $this->hasMany(Topic::className(), ['group_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return GroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupQuery(get_called_class());
    }
}
