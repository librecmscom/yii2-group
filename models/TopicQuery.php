<?php

namespace yuncms\group\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Topic]].
 *
 * @see Topic
 */
class TopicQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Topic[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Topic|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
