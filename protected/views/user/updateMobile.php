<div class="ms_wrap">
            <ul class="breadcrumb w960">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li>
                  <a href="/index.php?r=user">个人中心</a> <span class="divider">/</span>
                </li>
                <li class="active">手机号码设置</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main fr w79p">
              	<div class="hd bgl_c">
                	<h2 class="pl15 g3">手机号码设置</h2>
                </div>
                <div class="bd p15 bd1">
                    <form class="q_login_form" action="/index.php?r=user/updateMobile" method="post" id="q_mobile_form">
                <?php if(isset($_GET['direct']) && $_GET['direct']): ?>
                    <input type="hidden" name="direct" value="<?php echo $_GET['direct'];?>" />
                <?php endif;?>
                        <?php if(isset(Yii::app()->user->mobile) && Yii::app()->user->mobile):?>
                        <label><span class="label_des" style="width: 120px;">当前手机号码:</span><span class="s_input uneditable-input"><?php echo Yii::app()->user->mobile;?></span></label>
                        <?php endif;?>
                        <label><span class="label_des" style="width: 120px;"><b class="r3">&lowast;</b>新的手机号码:</span><input type="text" class="s_input" name="mobile" id="mobile"/></label>                                       
                        <div class="mt10" style="padding-left:124px;"><span class="add_tbn_red"><input type="button" value="确定" class="btn_red" onclick="updateMobileAction(this)" data-loading-text="请稍候..." autocomplete="off"/></span></div>            </form>                 	
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
	$("#q_mobile_form").validate({
		rules: {
			mobile: {
				required: true,
				digits: true,
				minlength:5,
				maxlength:15
			}
		}
	});
});
</script>