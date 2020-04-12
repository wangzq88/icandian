<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta property="qc:admins" content="00110467276131641166375" /> 
<meta property="wb:webmaster" content="359c8b4e60c650f5" />
<title>iCanDian-网上餐店、网上订餐、叫外卖</title>
<!-- Bootstrap -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" type="text/css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery/jquery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/index.js"></script>
</head>

<body>
<div id="wrap">
     <?php $this->renderDynamic('renderDynamicHeader'); ?>
    <!-- #header -->
    <div id="main" class="container">
    <?php echo $content; ?>
    </div>
     <!-- #main -->
        <div id="footer" class="container">
            <dl class="footer_info span12">
                <dd class="bot_links">
                    <a href="javascript:void(0);" onClick="shopApply();">商家入驻</a> | <a href="<?php echo $this->createUrl('site/contact'); ?>">联系我们</a> 
                </dd>
                <dd class="copyright">Copyright © 2013 iCanDian All Rights Reserved</dd>
            </dl>
        </div>
        <!-- #footer -->    
</div>    
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/rsa/all.js"></script>
</body>
</html>