<?php

namespace yuncms\group\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Topic]].
 *
 * @see GroupTopic
 */
class GroupTopicQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return GroupTopic[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return GroupTopic|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
