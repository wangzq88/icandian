<div id="header">
        <div class="top_nav_bar bgl_a">
            <div class="container">
                <div class="row">
                    <div class="span12 clearfix">
                        <h1 id="logo" onClick="location.href='/';" style="cursor:pointer">网上餐店</h1>
                        <!--/#logo-->
                        <ul class="top_menu_a">
                            <li class="bg"><a href="/index.php?r=user">个人中心</a></li>
                            <?php if (!Yii::app()->user->isGuest):?>
                            	<?php if(Yii::app()->user->flag==2):?>
                                <li class="bg"><a href="/index.php?r=manage">餐店管理</a></li>
                                <?php elseif(Yii::app()->user->flag==3):?>
                                <li class="bg"><a href="/index.php?r=admin">后台管理</a></li>
                            	<?php endif;?> 
                            <?php endif;?> 
                            <li><a href="<?php echo $this->createUrl('feedback/index'); ?>">反馈留言</a></li>
                        </ul>
                         <?php if (!Yii::app()->user->isGuest) :?>
                        <ul class="top_menu_c fr">
                          <li class="welcome_tip">欢迎您的到来：</li>
                          <li class="dropdown" id="fat-menu">
                           <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo Yii::app()->user->name;?><span class="tip_arrow_wt ml5"></span></a>
                            <ul class="dropdown-menu tc">
                              <li><a href="/index.php?r=user"><i class="icon-user"></i> 个人中心</a></li>
                              <li><a href="/index.php?r=CollectionFood"><i class="icon-star"></i> 我的收藏</a></li>
                              <li><a href="/index.php?r=order"><i class="icon-list-alt"></i> 订单记录</a></li>
                              <li><a href="/index.php?r=site/logout"><i class="icon-off"></i> 安全退出</a></li>
                            </ul>
                          </li>
                        </ul>        
                         <?php endif;?>                
                        <!--/.top_menu_a-->
                        <ul class="top_menu_b">
                            <li class="shop_car"><i class="icon-shopping-cart icon-white"></i><span class="pl5">购餐车(<a href="javascript:void(0);" id="shop-cart-food-count" onClick="if(loginPopoverFilter('您必须登录后才能进入结算喔')) location.href='/index.php?r=user/checking';">0</a>)</span></li>
                        <?php if (Yii::app()->user->isGuest) :?>
                            <li class="smat_links bgl_b">
                                <dl>
                                    <dd><a href="javascript:void(0);" class="g6" onclick='toWeiboLogin()'><span class="i_sina">&nbsp;</span>微博登录</a></dd>
                                    <dd><a href="javascript:void(0);" class="g6" onclick='toQzoneLogin()'><span class="i_QQ">&nbsp;</span>QQ登录</a></dd>
                                    <dd><a href="#mix-login-Modal" class="r3 self-modal-button" data-toggle="modal" index="0">登录</a></dd>
                                    <dd style="border-right:none"><a href="#mix-login-Modal" class="r3 self-modal-button" data-toggle="modal" index="1">注册</a></dd>
                                </dl>
                            </li>
                      <?php endif;?>                            
                        </ul> 
                        <!--/.top_menu_b-->             
                    </div>
                </div>        
            </div>
        </div>    	
    	<!--/ 顶部导航-->
    </div>
    <!-- #header -->
    
     <!-- 隐藏结构 -->
        <?php if (Yii::app()->user->isGuest) :?>    
        <div class="modal none" id="mix-login-Modal">
          <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <ul class="login_box" id="quick_login_box">
                <li class="q_login active"><a href="#q_login_tab" data-toggle="tab">快速登录</a></li>
                <li class="q_regedit"><a href="#q_regedit_tab" data-toggle="tab">快速注册</a></li>
            </ul>
          </div>
          <div class="modal-body tab-content">
            <form class="q_login_form active none" action="/index.php?r=site/ajaxLogin" method="post" id="q_login_tab">
                <label><span class="label_des">账号:</span><input type="text" class="s_input" name="email"/></label>
                <label><span class="label_des">密码:</span><input type="password" class="s_input" name="password" onKeyPress="return trigerLoginAction(event);" onKeyUp="return trigerLoginAction(event);" onKeyDown="return  trigerLoginAction(event);"/><span class="pl15 f12" style="color:#006797">忘记密码？</span></label>
                <div class="pl85"><span class="add_tbn_red"><input type="button" value="登录" class="btn_red" onClick="selfLoginAction(this);" data-loading-text="请稍候..." autocomplete="off" id="ajax-login-button"/></span></div>
            </form>
            <form class="q_login_form none" action="/index.php?r=userApply/create" method="post" id="q_regedit_tab">
                <label><span class="label_des"><b class="r3">&lowast;</b>电子邮箱:</span><input type="email" class="s_input required email" name="email" /></label>
                <label><span class="label_des"><b class="r3">&lowast;</b>用户名:</span><input type="text" class="s_input required" name="username"/></label>
                <label><span class="label_des"><b class="r3">&lowast;</b>设置密码:</span><input type="password" class="s_input required" name="regedit_password" id="regedit_password"/></label>
                <label><span class="label_des"><b class="r3">&lowast;</b>确认密码:</span><input type="password" class="s_input required" name="regedit_repeat_password" id="regedit_repeat_password"/></label>                
				<div class="pl85"><span class="add_tbn_red"><input type="button" value="立即注册" class="btn_red" onClick="registerUserAction(this)" data-loading-text="请稍候..." autocomplete="off"/></span></div>            </form>            
          </div>
          <div class="modal-footer">
            <dl class="other_login">
                <dt class="fb g3">使用其他帐号登录：</dt>
                <dd><a class="g6 pr10" onclick='toWeiboLogin()' href="javascript:void(0);"><span class="i_sina">&nbsp;</span> 微博登录</a><a class="g6" onclick='toQzoneLogin()' href="javascript:void(0);"><span class="i_QQ">&nbsp;</span> QQ登录</a></dd>
            </dl>
          </div>
        </div>    
  <script type="text/javascript">
jQuery(function($) {
	$.validator.setDefaults({ 
		errorElement:"span",
		errorClass: "help-block"
	});	
    $("#q_regedit_tab").validate({
	  rules: {
		regedit_password:{
			required:true,
			minlength:6 
		},
		regedit_repeat_password: {
		  equalTo: "#regedit_password"
		}
	  }
	});
	$(".self-modal-button").click(function() {
		if($(this).attr('index') == '0') {
			$('#quick_login_box a:first').tab('show');
		} else {
			$('#quick_login_box a:last').tab('show');
		}
	});		
	


});  

function trigerLoginAction(event)
{
	var id = 'ajax-login-button';
	return enterKeyEventHandler(event,(function(id) {
		return function() {
			selfLoginAction(document.getElementById(id));
		}
	})(id));
}

  function registerUserAction(obj)
  {
	  var email = $.trim($('#q_regedit_tab').find('input[name="email"]').val());
	  var userName = $.trim($('#q_regedit_tab').find('input[name="username"]').val());
	  var password = $.trim($('#q_regedit_tab').find('input[name="regedit_password"]').val());

	  if(!$("#q_regedit_tab").valid()) return false;	  
	  $(obj).button('loading');
      var rsa = new RSAKey();
	  rsa.setPublic(public_key, public_length);	
	  var password = rsa.encrypt(password);	  
	  $.post($('#q_regedit_tab').attr('action'),{email:email,username:userName,password:password},function(data) {
		  $(obj).button('reset');
		  var style = data.success > 0 ? 'alert-success':'alert-error';
		  addFormTip($(obj).closest('form'),data.info,style);
	  });	  
  }
  
  function selfLoginAction(obj)
  {
	  var email = $.trim($('#q_login_tab').find('input[name="email"]').val());
	  var password = $.trim($('#q_login_tab').find('input[name="password"]').val());
	  if(email == '' || password=='') return false;
	  $(obj).button('loading');
      var rsa = new RSAKey();
	  rsa.setPublic(public_key, public_length);	
	  var password = rsa.encrypt(password);
	  $.post($('#q_login_tab').attr('action'),{email:email,password:password},function(data) {
		   $(obj).button('reset');
		   var style = data.success? 'alert-success':'alert-error';
		   addFormTip($('#q_login_tab'),data.info,style);
		  if(data.success) {
			  window.location.reload();
		  } 
	  });
  }
</script>            
<?php endif;?>    