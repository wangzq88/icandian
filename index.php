<?php
error_reporting ( 7 );
session_start();
if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0') !== false || strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0') !== false)
{
    header('Location: /ie.html');
    exit();
}
// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

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
