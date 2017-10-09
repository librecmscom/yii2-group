<?php

namespace yuncms\group\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Fans]].
 *
 * @see GroupMember
 */
class GroupOrderQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
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
}
