# yii2-group
适用于Yii2的群组模块

[![Latest Stable Version](https://poser.pugx.org/yuncms/yii2-group/v/stable.png)](https://packagist.org/packages/yuncms/yii2-group)
[![Total Downloads](https://poser.pugx.org/yuncms/yii2-group/downloads.png)](https://packagist.org/packages/yuncms/yii2-group)
[![Reference Status](https://www.versioneye.com/php/yuncms:yii2-group/reference_badge.svg)](https://www.versioneye.com/php/yuncms:yii2-group/references)
[![Build Status](https://img.shields.io/travis/yiisoft/yii2-group.svg)](http://travis-ci.org/yuncms/yii2-group)
[![Dependency Status](https://www.versioneye.com/php/yuncms:yii2-group/dev-master/badge.png)](https://www.versioneye.com/php/yuncms:yii2-group/dev-master)
[![License](https://poser.pugx.org/yuncms/yii2-group/license.svg)](https://packagist.org/packages/yuncms/yii2-group)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require yuncms/yii2-group
```

or add

```
"yuncms/yii2-group": "~2.0.0"
```

to the `require` section of your `composer.json` file.

## 使用

Api模块

yuncms\group\api\Module

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