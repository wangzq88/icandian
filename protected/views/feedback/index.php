<div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li class="active">反馈留言</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main w100p">
                  <div class="hd bgl_c">
                      <h3 class="clearfix pl10 pr10"><a href="javascript:void(0);" class="fr btn btn-small mt5" id="want-to-feedback">我要评论</a><strong class="fb fl">反馈留言</strong></h3>
                  </div>
                  <div class="bd ms_comment_lst">
                  <?php if($feedback_list && is_array($feedback_list)):?>
                  	<?php foreach($feedback_list as $feedback): ?>
                      <dl class="mb10 clearfix">
                          <dt class="w8p fl"><img src="/images/avatar.jpg" class="small_pic"/></dt>
                          <dd class="w92p fr">
                              <div class="pr bg_fe bd3 p10">
                                  <span class="tip_arrow_yl pa"></span>
                                  <span class="tip_arrow_yl2 pa"></span>
                                  <p><strong class="f14 o3"><?php echo $feedback['username'];?></strong></p>
                                  <p class="ptb5 lh24"><?php echo $feedback['content'];?> </p>
                                  <p><span class="g9 pr10">发表时间：<?php echo date('Y-m-d H:i',$feedback['timestamp']);?></span><?php if(!Yii::app()->user->isGuest && Yii::app()->user->flag == 3):?><a href="javascript:void(0);" onclick="$(this).parent().next().show();"><span class="icon-comment"></span> 回复</a><?php endif;?></p>
                 				<?php if(!Yii::app()->user->isGuest && Yii::app()->user->flag == 3):?>
                                  <div class="pt15 none">
                                  	<form action="/index.php?r=feedback/replay" method="post" class="form-inline">
                                    	<input type="hidden" name="parent_id" value="<?php echo $feedback['id'];?>" />
                                        <input type="hidden" name="flag" value="<?php echo $feedback['flag'];?>" />
                                        <input type="text" name="content" value="" class="input-xxlarge"  onkeyup="return enterKeyEventHandler(event,(function(obj) {return function(){replayFeedbackAction(obj)}})($(this).next()));" required/>
                                        <button class="btn btn-danger" type="button" onclick="replayFeedbackAction($(this));" autocomplete="off" data-loading-text="请稍候...">提交</button>
                                    </form>
                                  </div>
                              </div>
                              <?php endif;?>
                          </dd>
                          <?php if($feedback['replay_list'] && is_array($feedback['replay_list'])):?>
                          	<?php foreach($feedback['replay_list'] as $replay):?>
                              <!--/回复-->
                              <dd class="w92p fr">
                                  <div class="c_repeat pr">
                                    <div class="bg_f bd6 p10">
                                          <span class="tip_arrow_dt pa"></span>
                                          <span class="tip_arrow_wt pa"></span>
                                          <p class="lh24"><?php echo $replay['content'];?></p>
                                          <p class="ptb5"><span class="o3 pr5"><?php echo $replay['username'];?></span><span class="g9 pr10">发表时间：<?php echo date('Y-m-d H:i',$replay['timestamp']);?></span></p>
                                      </div>                          
                                  </div>
                              </dd>                          
                              <?php endforeach;?>
                          <?php endif;?>
                      </dl>
                      <?php endforeach;?>
                  <?php endif;?>
                                                                                        

                     <?php $this->widget('BootstrapPaging',array('page'=>$page,'total_page' => $total_page,'friendly' => true,'url' => '/feedback.html')); ?>
                    <!--/ 分页-->
                    <?php if (!Yii::app()->user->isGuest) :?>
                    <form method="post" action="/index.php?r=feedback/create" class="form_message" id="form-feedback">
                        <fieldset>
                            <label class="g3 pl5 fm_tit">我要留言</label>
                            <p class="fm_type">留言类型：<label><input type="radio" name="flag" value="1"/>催单</label><label><input type="radio" name="flag" value="2" />网站错误</label><label><input type="radio" name="flag" value="3"/>功能建议</label><label><input type="radio" name="flag" value="4"/>投诉</label></p>
                            <p><textarea cols="5" rows="5" class="w90p" name="content"></textarea></p>
                            <?php if(CCaptcha::checkRequirements()): ?>
                            <p><?php $this->widget('CCaptcha',array('buttonLabel'=>'获取新的验证码')); ?>
                            <label class="f12">请输入验证码：<input type="text" class="span2" name="confirm_code" onblur="confirmCodeAction(this);" id="confirm_code"/></label></p>
                            <?php endif; ?>
                            <input type="button" class="btn_red" value="提交" autocomplete="off" data-loading-text="请稍候..."  onclick="createFeedbackAction(this);"/>
                        </fieldset>
                    </form>
                    <?php endif;?> 
                    <!--/留言表单-->                                 
                  </div>                        
              </div>
              <!--/中心右边栏-->              

            </div>
			
        </div>
<?php 
	$captcha = Yii::app()->getController()->createAction('captcha');
	$code = $captcha->getVerifyCode(true);
	$hash = $captcha->generateValidationHash(strtolower($code));
?>        
<script type="text/javascript">
var is_submit = false;

function confirmCodeAction(obj)
{
	var value = $(obj).val();
	var hash = $('body').data('captcha.hash');
	if (hash == null)
		hash = '<?php echo $hash; ?>';
	else
		hash = hash[0];
	for(var i=value.length-1, h=0; i >= 0; --i) h+=value.toLowerCase().charCodeAt(i);
	$('#confirm_code').nextAll().remove();
	if(h != hash) {
		is_submit = false;
		$('#confirm_code').after('<span class="help-inline">验证码错误</span>');
	} else {
		is_submit = true;
	}
}

function createFeedbackAction(obj)
{
	if(loginPopoverFilter('您必须登录后才能进行该操作喔')) {
		if($.trim($('#form-feedback').find('textarea').val()) == '') {
			addFormTip($('#form-feedback'),'请填写留言');
			return false;
		}
		if(!$('#form-feedback :radio').is('[checked="checked"]')) {
			addFormTip($('#form-feedback'),'请选择留言类型');
			return false;			
		}
		if(!is_submit) {
			addFormTip($('#form-feedback'),'请输入验证码');
			return false;
		}
		$(obj).button('loading');
		$.post($('#form-feedback').attr('action'),$('#form-feedback').serialize(),function(data){
			$(obj).button('reset');
			var style = 'alert-error';
			if(data.success) {
				style = 'alert-success';
				$(obj).prev('p').children('a').trigger('click');
				document.getElementById('form-feedback').reset();
				$(".ms_comment_lst").prepend(data.content);
			}
			addFormTip($('#form-feedback'),data.info,style);
		});
	}
}

function replayFeedbackAction(obj) {
	obj.button('loading');
	$.post(obj.parent('form').attr('action'),obj.parent('form').serialize(),function(data){
		obj.button('reset');
		if(data.success) {
			obj.parents('dd').after(data.content);
			obj.prev('input[name="content"]').val('');
		} else {
			modalAlert('提示',data.info);
		}
	});	
}

$('#want-to-feedback').click(function() {
	if(loginPopoverFilter('您必须登录后才能进行该操作喔')) {
		$("#form-feedback").find('textarea').focus();
	}
});
</script>