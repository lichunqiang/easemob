Easemob SDK
------------

Easemob Restful Api SDK for PHP.

[![Build Status](https://img.shields.io/travis/lichunqiang/easemob.svg?style=flat-square)](http://travis-ci.org/lichunqiang/easemob)
[![version](https://img.shields.io/packagist/v/light/easemob.svg?style=flat-square)](https://packagist.org/packages/light/easemob)
[![Download](https://img.shields.io/packagist/dt/light/easemob.svg?style=flat-square)](https://packagist.org/packages/light/easemob)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/lichunqiang/easemob.svg?style=flat-square)](https://scrutinizer-ci.com/g/lichunqiang/easemob)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/lichunqiang/easemob.svg?style=flat-square)](https://scrutinizer-ci.com/g/lichunqiang/easemob)
[![Contact](https://img.shields.io/badge/weibo-@chunqiang-blue.svg?style=flat-square)](http://weibo.com/chunqiang)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist light/easemob "*"
```

or add

```
"light/easemob": "*"
```

to the require section of your `composer.json` file.


Usage
-----

```
use Monolog\Logger;

$easemob = new Easemob([
  'enterpriseId' => 'enterpriseID',
  'appId' => 'appID',
  'clientId' => 'clientID',
  'clientSecret' => 'clientSecret',
  'log' => [
      'file' => '/var/easemob.log',
      'level' =>Logger::DEBUG,
  ],
]);
```

### 用户系统

```
$user = $easemob->user;
```

* 注册单个用户 `$user->register(['username' => 11, 'password' => 'password'])`
* 注册多个用户 `$user->register([['username' => 1, 'password' => 'password'], ['username' => 2, 'password' => 'password'])`
* 获取单个用户 `$user->one(1)`
* 获取所有用户 `$user->all(/*$cursor*//*, $limit = 20*/)`
* 删除单个用户 `$user->remove(1)`
* 删除多个用户 `$user->batchRemove(/*$count = 100*/)`
...

### 消息系统

```
$message = $user->message;

//发送文本消息
$text = new \light\Easemob\Message\Text(['msg' => 'hello']);
$message->send($text);
```
....


License
-------

![MIT](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)
