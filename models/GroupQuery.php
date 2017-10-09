<?php

namespace yuncms\group\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Group]].
 *
 * @see Group
 */
class GroupQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Group[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Group|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * 最新圈子
     * @return $this
     */
    public function newest()
    {
        return $this->orderBy(['(start_time - now())' => SORT_ASC]);
    }

    /**
     * 热门圈子
     * 按报名人数计算，热度衰减指数是1.8
     */
    public function hottest()
    {
        return $this->orderBy(['(applicants / pow((((UNIX_TIMESTAMP(NOW()) - created_at) / 3600) + 2),1.8) )' => SORT_DESC]);
    }

    /**
     * 我发起的
     * @return $this
     */
    public function my()
    {
        return $this->andWhere(['user_id' => Yii::$app->user->id]);
    }

    /**
     * 我参与的
     * @return $this
     */
    public function myJoin()
    {
        return $this->innerJoinWith([
            'fans' => function ($query) {
                $query->where([
                    GroupMember::tableName() . '.user_id' => Yii::$app->user->id,
                    GroupMember::tableName() . '.status' => GroupMember::STATUS_ACTIVE
                ]);
            }
        ]);
    }
}
