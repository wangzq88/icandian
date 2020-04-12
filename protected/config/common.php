<?php
date_default_timezone_set('PRC');//设置时间区域
mb_internal_encoding('UTF-8');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', str_replace(DS.'protected'.DS.'config','',dirname(__FILE__)));//idingcan 根路径
define('TMP_PATH', ROOT_PATH.DS.'tmp');//临时目录，用于存放上传的临时图片或者附件
define('SHOP_UPLOAD_PATH', str_replace('protected'.DS.'config','uploads'.DS.'shop',dirname(__FILE__)));//上传商家相关信息的根目录
define('SHOP_UPLOAD_URL', '/uploads/shop');//上传商家相关信息的URL，用于录入数据库
define('AVATAR_UPLOAD_URL', '/uploads/avatar');
define('DEFAULT_AVATAR', '/images/avatar.jpg');
define('SHOP_DEFAULT_LOGO', '/images/shop/food.jpg');//前台餐店默认logo
define('FOOD_DEFAULT_LOGO', '/images/banner/7792647_m.jpg');//前台美食默认图片
define('SMTPSERVER', 'ssl://smtp.gmail.com');
define('SMTPUSER', 'woqilin@gmail.com');
define('SMTPPASS', '');
define('SMTPPORT', '465');
define('ADMIN_ID', '1');//系统管理员ID，在系统发送信息的场合会用到
define('ADMIN_NAME', '管理员');//系统管理员名称
define('FOOD_PAGE_COUNT', '12');//前台商家详细页面每一页的美食数目
?>