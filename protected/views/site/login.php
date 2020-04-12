<style type="text/css">
.pc_main .bd{ padding:40px 102px;}
</style>
<div class="ms_wrap">
    <ul class="breadcrumb w960 mt10">
        <li>
          <a href="/">首页</a> <span class="divider">/</span>
        </li>
        <li class="active">登录</li>
    </ul>
    <div class="p10 bg_opacity clearfix">
   		<div class="pc_main w100p">     
            <div class="hd bgl_c">
                <h3 class="clearfix pl10 pr10">   
                	<strong class="fb fl">登录</strong>                      
                </h3>
            </div>        
            <div class="bd">
                <form class="form-horizontal" id="login-form" action="/index.php?r=site/login" method="post">
                    <input type="hidden" name="LoginForm[encryption]" id="LoginForm_encryption" />
                  <div class="control-group">
                    <label class="control-label" for="inputEmail">邮箱：</label>
                    <div class="controls">
                      <input type="text" id="inputEmail" placeholder="你注册的邮箱地址" name="LoginForm[email]">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="inputPassword">密码：</label>
                    <div class="controls">
                      <input type="password" id="inputPassword" placeholder="输入你的密码" name="LoginForm[password]">
                    </div>
                  </div>
                    <div class="control-group">
                        <div class="controls">
                    <a href="javascript:void(0);" onclick='toQzoneLogin()'><img src="/images/login/qq_login.png"></a>
                     <a href="javascript:void(0);" onclick='toWeiboLogin()'><img src="/images/login/weibo_login.png"></a>
                        </div>         
                    </div>       
                  <div class="control-group">
                    <div class="controls">
                      <label class="checkbox">
                        <input type="checkbox"> 记住我
                      </label>
                      <button type="submit" class="btn">登录</button>
                    </div>
                  </div>      
                </form>    
            </div>
        </div>
    </div>
</div>