<?php
session_start();
/**
 * This is the bootstrap file for test application.
 * This file should be removed when the application is deployed for production.
 */

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/test.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

require($yii);
//@date:2012-11-11 @author:WangZQ @purpose:引进自定义的常量
require(dirname(__FILE__).'/protected/config/common.php');
//@date:2013-3-15 @author:WangZQ @purpose:引进公共函数
require(dirname(__FILE__).'/api/commomFunction.php');
//客户端的身份标识
if (!isset($_COOKIE['identity_flag']))
	setcookie('identity_flag',time(),0,'/');
//用于判断是否要清除存储在客户端的数据
if (!isset($_COOKIE['access_count']))
	setcookie('access_count',0,0,'/');		
else 
	setcookie('access_count',$_COOKIE['access_count']+1,0,'/');		
Yii::createWebApplication($config)->run();
