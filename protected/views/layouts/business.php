<!DOCTYPE html>  
<html>  
<head>  
    <meta charset="UTF-8">  
    <meta http-equiv="X-UA-Compatible" content="chrome=1"> 
    <title>餐店管理</title>  
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/themes/default/easyui.css">  
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/themes/icon.css">  
<style type="text/css">
body {
    font-family: '微软雅黑','宋体';
	font-size:16px;
}
.demo-info {
	background: #FFFEE6;
	color: #8F5700;
	padding: 12px;
}
.demo-tip {
	width: 16px;
	height: 16px;
	margin-right: 8px;
	float: left;
}	
.panel {
    font-size: 14px;
}
.clear {clear:both;margin:0;padding:0;width:0;height:0;}
	</style>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/jquery-1.8.0.min.js"></script>  
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/jquery.easyui.min.js"></script>  
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
    <script type="text/javascript">
 //友好提示
 function show_friendly_tip(tip,obj,position) {
	 $("#easy_ui_friendly_tip").remove();
	var html = '<div class="demo-info" id="easy_ui_friendly_tip">'+
		'<div class="demo-tip icon-tip"></div>'+
		'<div>'+tip+'</div>'+
	'</div>';
	if(position == 1) {
		obj.prepend(html);
	} else {
		obj.append(html);
	}
	setTimeout('jQuery("#easy_ui_friendly_tip").remove();',8000);
 }	
 
 function enterKeyEventHandler(event,callback) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (!$.isFunction(callback)) {
        try{
            console.error('第二个参数请传递一个函数');
        }catch(e){}
        return false;
    }		
	if (keyCode == 13) {
		if(event.type == 'keyup') {
			callback();	
		}
		return false
	}	
	return true;	
}	

function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}
function getCookie(c_name)
{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	{
 		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  		x=x.replace(/^\s+|\s+$/g,"");
  		if (x==c_name)
    	{
    		return unescape(y);
    	}
  	}
}
	</script>
</head>  
<body>
<?php echo $content; ?>
</body>  
</html>