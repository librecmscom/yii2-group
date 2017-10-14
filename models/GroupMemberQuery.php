<?php

namespace yuncms\group\models;

/**
 * This is the ActiveQuery class for [[GroupMember]].
 *
 * @see GroupMember
 */
class GroupMemberQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /*public function active()
    {
        return $this->andWhere(['status' => GroupMember::STATUS_PUBLISHED]);
    }*/

    /**
     * @inheritdoc
     * @return GroupMember[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return GroupMember|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * 查询今日新增
     * @return $this
     */
    public function dayCreate()
    {
        return $this->andWhere('date(created_at)=date(NOW())');
    }

    /**
     * 查询本周新增
     * @return $this
     */
    public function weekCreate()
    {
        return $this->andWhere('month(FROM_UNIXTIME(created_at)) = month(curdate()) AND week(FROM_UNIXTIME(created_at)) = week(curdate())');
    }

    /**
     * 查询本月新增
     * @return $this
     */
    public function monthCreate()
    {
        return $this->andWhere('month(FROM_UNIXTIME(created_at)) = month(curdate()) AND year(FROM_UNIXTIME(created_at)) = year(curdate())');
    }

    /**
     * 查询本年新增
     * @return $this
     */
    public function yearCreate()
    {
        return $this->andWhere('year(FROM_UNIXTIME(created_at)) = year(curdate())');
    }

    /**
     * 查询本季度新增
     * @return $this
     */
    public function quarterCreate()
    {
        return $this->andWhere('quarter(FROM_UNIXTIME(created_at)) = quarter(curdate())');
    }
}
