<?php

namespace yuncms\group\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Fans]].
 *
 * @see Fans
 */
class FansQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Fans[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Fans|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
