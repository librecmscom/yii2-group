<?php

namespace yuncms\group\models;

use Yii;
use yii\db\ActiveRecord;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%group_topic}}".
 *
 * @property int $id 主键
 * @property int $group_id 群组ID
 * @property int $user_id 用户ID
 * @property int $model_id 资源模型ID
 * @property string $model 资源模型名称
 * @property string $subject 资源名称
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property Group $group
 * @property User $user
 */
class Topic extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_topic}}';
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
            [['group_id', 'user_id', 'model_id', 'status'], 'integer'],
            [['model_id', 'model'], 'required'],
            [['model', 'subject'], 'string', 'max' => 255],
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
            'id' => Yii::t('group', 'Id'),
            'group_id' => Yii::t('group', 'Group Id'),
            'user_id' => Yii::t('group', 'User Id'),
            'model_id' => Yii::t('group', 'Model Id'),
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
     * @return TopicQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TopicQuery(get_called_class());
    }
}
