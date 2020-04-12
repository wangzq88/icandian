<!DOCTYPE html>  
<html>  
<head>  
    <meta charset="UTF-8">  
    <title>No collapsible button in Layout - jQuery EasyUI Demo</title>  
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/themes/default/easyui.css">  
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/themes/icon.css">  
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/jquery-1.8.0.min.js"></script>  
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/jquery.easyui.min.js"></script>  
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
</head>  
<body>        
<div style="padding:10px 0 10px 60px;">  
 <form id="ff" method="post" action="/index.php?r=shop/update">  
            <table>  
                <tr>  
                    <td>餐店名称：</td>  
                    <td><input class="easyui-validatebox" type="text" name="Shop[shop_name]" data-options="required:true"></input></td>  
                </tr>  
                <tr>  
                    <td>餐店介绍：</td>  
                    <td><textarea name="Shop[shop_description]" style="height:60px;"></textarea></td>  
                </tr>  
                <tr>  
                    <td>所在省:</td>  
                    <td><input class="easyui-validatebox" type="text" name="Shop[shop_province]" data-options="required:true"></input></td>  
                </tr>  
                <tr>  
                    <td>所在市:</td>  
                    <td><input class="easyui-validatebox" type="text" name="Shop[shop_city]" data-options="required:true"></input></td>  
                </tr> 
                <tr>  
                    <td>所在区域:</td>  
                    <td><input class="easyui-validatebox" type="text" name="Shop[shop_region]" data-options="required:true"></input></td>  
                </tr>
                <tr>  
                    <td>具体地段:</td>  
                    <td><input class="easyui-validatebox" type="text" name="Shop[shop_area]" data-options="required:true"></input></td>  
                </tr>                                
               </table>
               </form>
</div>
<div style="background:#fafafa;text-align:center;padding:5px">  
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm()">保存</a>  
        </div>  
 <script>  
        function submitForm(){  
            $('#ff').form('submit');  
        }  
    </script>          
</body>  
</html>