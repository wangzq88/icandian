<?php 
if ($_GET['flag'] == 1):
	$text = '微博';
	$action = '/index.php?r=site/createNewUserByWeibo';
	$bindAction = '/index.php?r=site/bindUserByWeibo';
else:
	$text = 'QQ';
	$action = '/index.php?r=site/createNewUserByQQ';
	$bindAction = '/index.php?r=site/bindUserByQQ';
endif;
?>
<style type="text/css">
#create-form,#login-form  {position: relative;border:1px solid #e5e5e5; padding:20px; margin:20px;border-radius: 10px;margin-bottom:50px;}
#create-form:after {
content: "一键创建帐号";
position: absolute;
top: -1px;
left: -1px;
padding: 5px 10px;
font-size: 14px;
font-weight: bold;
background-color: #f5f5f5;
border: 1px solid #ddd;
color: #9da0a4;
-webkit-border-radius: 4px 0 4px 0;
-moz-border-radius: 4px 0 4px 0;
border-radius: 4px 0 4px 0;
}
#login-form:after {
content: "绑定已有帐号";
position: absolute;
top: -1px;
left: -1px;
padding: 5px 10px;
font-size: 14px;
font-weight: bold;
background-color: #f5f5f5;
border: 1px solid #ddd;
color: #9da0a4;
-webkit-border-radius: 4px 0 4px 0;
-moz-border-radius: 4px 0 4px 0;
border-radius: 4px 0 4px 0;
}
</style>
<div class="ms_wrap">
    <ul class="breadcrumb w960 mt10">
        <li>
          <a href="/">首页</a> <span class="divider">/</span>
        </li>
        <li class="active"><?php echo $text;?>登录</li>
    </ul>
    
    <div class="hd bgl_c">
    	<h3 class="clearfix pl10 pr10"><?php echo $text;?>登录</h3>
    </div>
    <div class="bd1 bg_f clearfix">
 			<div class="lh30 f16 ml20 mt10"><span class="label label-success">！</span> <img src="<?php echo $_COOKIE['avatar'] ? $_COOKIE['avatar']:DEFAULT_AVATAR;?>" alt="头像" width="50" height="50" style="vertical-align: bottom;"/>来自<?php echo $text; ?>登录的<?php echo $_COOKIE['username'];?>，您好！现在可以连接 <?php echo Yii::app()->name;?> 了</div> 
        	
            <form class="form-horizontal" id="create-form" action="<?php echo $action;?>" method="post">
                <div class="control-group">
                  <div class="controls" style="font-size:14px;">
                        非 iCanDian 会员，一键创建帐号
                  </div>
                </div>                             
                <div class="control-group">
                  <div class="controls">
                       <button type="submit" class="btn">创建帐号</button>
                  </div>
                </div>                  
            </form>
            
            <form class="form-horizontal" id="login-form" action="<?php echo $bindAction?>" method="post">
              <fieldset>
                <input type="hidden" name="LoginForm[encryption]" id="LoginForm_encryption" />      
                <div class="control-group" style="font-size:14px;">
                  <div class="controls">
                        iCanDian 会员，使用已有用户名连接
                  </div>
                </div>                                
                <div class="control-group">
                  <label class="control-label" for="inputEmail">邮箱：</label>
                  <div class="controls">
                   <input type="email" id="inputEmail" name="LoginForm[email]">
                    <!--<span class="help-inline">错误信息</span>   -->   
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="inputPassword">密码：</label>
                  <div class="controls">
                    <input type="password" id="inputPassword" name="LoginForm[password]" />
                    <!--<span class="help-inline">正确信息</span>-->
                  </div>
                </div>
                <div class="control-group">
                  <div class="controls">
                        <label class="checkbox" style="margin-bottom: 5px;">
                            <input type="checkbox" />
                            记住我
                        </label>
                        <button type="submit" class="btn">登录</button>
                  </div>
                </div>
            
                
              </fieldset>
            </form>            
                      
    </div>
    </div>