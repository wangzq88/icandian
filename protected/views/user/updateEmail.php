<div class="ms_wrap">
            <ul class="breadcrumb w960">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li>
                  <a href="/index.php?r=user">个人中心</a> <span class="divider">/</span>
                </li>
                <li class="active">邮箱设置</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main fr w79p">
              	<div class="hd bgl_c">
                	<h2 class="pl15 g3">邮箱设置</h2>
                </div>
                <div class="bd p15 bd1">
                    <form class="q_login_form" action="/index.php?r=user/updateEmail" method="post" id="q_email_form">
                    	<input type="hidden" name="confirm_code" value="<?php echo $_REQUEST['confirm_code'];?>" />
                        <?php if(Yii::app()->user->email):?>
                        <label><span class="label_des">当前邮箱:</span><span class="s_input uneditable-input"><?php echo Yii::app()->user->email;?></span></label>
                        <?php endif;?>
                        <label><span class="label_des"><b class="r3">&lowast;</b>新的邮箱:</span><input type="email" class="s_input email required" name="email" id="email"/></label>                                       
                        <div class="mt10 pl85"><span class="add_tbn_red"><input type="button" value="确定" class="btn_red" onclick="updateEmailAction(this)" data-loading-text="请稍候..." autocomplete="off"/></span></div>            </form>                 	
                </div>
              </div>
              <!--/中心右边栏-->              
              <?php $this->widget('UserMenu'); ?>
              <!--/中心左边栏-->
            </div>
			
        </div>
<script type="text/javascript">
 $(document).ready(function(){
	$.validator.setDefaults({ 
		errorElement:"span",
		errorClass: "help-inline"
	});		 
	addFormTip($("#q_email_form"),'您现在可以设置邮箱帐号了，请在下面的框中输入您新的邮箱。提交后，请耐心稍后，这需要一段时间','alert-info');
    $("#q_email_form").validate();
  });
</script>