<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\group;


use Yii;
use yii\di\Instance;
use yii\caching\Cache;
use yii\base\InvalidConfigException;

/**
 * Class Module
 * @package yuncms\group
 */
class Module extends \yii\base\Module
{
    /**
     * @var string|Cache 缓存实例
     */
    public $cache = 'cache';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, Cache::className());
        }
    }
}