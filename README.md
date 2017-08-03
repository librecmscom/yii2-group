# yii2-group
适用于Yii2的群组模块

[![Latest Stable Version](https://poser.pugx.org/yuncms/yii2-group/v/stable.png)](https://packagist.org/packages/yuncms/yii2-group)
[![Total Downloads](https://poser.pugx.org/yuncms/yii2-group/downloads.png)](https://packagist.org/packages/yuncms/yii2-group)
[![Reference Status](https://www.versioneye.com/php/yuncms:yii2-group/reference_badge.svg)](https://www.versioneye.com/php/yuncms:yii2-group/references)
[![Build Status](https://img.shields.io/travis/yiisoft/yii2-group.svg)](http://travis-ci.org/yuncms/yii2-group)
[![Dependency Status](https://www.versioneye.com/php/yuncms:yii2-group/dev-master/badge.png)](https://www.versioneye.com/php/yuncms:yii2-group/dev-master)
[![License](https://poser.pugx.org/yuncms/yii2-group/license.svg)](https://packagist.org/packages/yuncms/yii2-group)


Installation
------------

Next steps will guide you through the process of installing yii2-admin using [composer](http://getcomposer.org/download/). Installation is a quick and easy three-step process.

### Step 1: Install component via composer

Either run

```
composer require --prefer-dist yuncms/yii2-group
```

or add

```json
"yuncms/yii2-group": "~2.0.0"
```

to the `require` section of your composer.json.

## 使用

前台模块

yuncms\group\frontend\Module

后台模块

yuncms\group\backend\Module

###Url规则
````
'groups'=>'group/group/index',
'group/detail/<id:[\w+]+>' => 'group/group/view',
````



## License

This is released under the MIT License. See the bundled [LICENSE](LICENSE)
for details.