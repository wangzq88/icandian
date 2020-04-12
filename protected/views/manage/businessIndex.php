<!DOCTYPE html>  
<html>  
<head>  
    <meta charset="UTF-8">  
    <title>餐厅管理</title>  
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/themes/default/easyui.css">  
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/themes/icon.css">  
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/jquery-1.8.0.min.js"></script>  
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/jquery.easyui.min.js"></script>  
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
    
</head>  
<?php if (!Yii::app()->user->isGuest && Yii::app()->user->flag ==2 ) :?>
<frameset cols="240,*" rows="*" id="mainFrameset">
        <frame frameborder="0" id="frame_navigation"
        src="/index.php?r=manage/nav"
        name="frame_navigation" />
        <frame frameborder="0" id="frame_content"
        src="/index.php?r=bShop/index"
        name="frame_content" />
        <noframes>
        <body>
            <p>你的的浏览器太落后啦！</p>
        </body>
    </noframes>
</frameset>
<?php elseif(!Yii::app()->user->isGuest):?>
<script type="text/javascript">
location.href = '/';
</script>
<?php else:?>
<script type="text/javascript">
location.href = '/index.php?r=site/login';
</script>
<?php endif; ?>
</html>