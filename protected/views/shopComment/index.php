<div class="row">
    <div class="span12">
        <div class="banner mtb10"><img src="<?php echo $shop['shop_banner'] ? $shop['shop_banner']:'/images/banner/banner_1.jpg';?>" alt="pic" width="960"/></div>
        <!--/banner-->
        <div class="ms_wrap">
             <?php $this->widget('ShopHeader',array('shop'=>$shop,'menu' => false,'title'=>'留言板')); ?>
            <!--/ 商家基本信息-->
            <div class="mt10">
                <div class="hd bgl_c">
                    <h3 class="clearfix pl10 pr10"><a href="javascript:void(0);" class="fr btn btn-small mt5" id="want-to-comment">我要留言</a><strong class="fb fl">留言板</strong></h3>
                </div>
                <div class="bd ms_comment_lst">
                	<?php if($comment_list && is_array($comment_list)):?>
                    	<?php foreach($comment_list as $comment):?>
                    <dl class="mb10 clearfix">
                        <dt class="w10p fl"><img src="<?php echo $comment['avatar'] ? $comment['avatar']:'/images/avatar.jpg'?>" class="small_pic"/></dt>
                        <dd class="w90p fr">
                            <div class="pr bg_fe bd3 p10">
                                <span class="tip_arrow_yl pa"></span>
                                <span class="tip_arrow_yl2 pa"></span>
                                <p class="clearfix"><span class="fr">发表时间：<?php echo date('Y-m-d H:i',$comment['timestamp']);?></span><strong class="f14 fl o3"><?php echo $comment['username'];?></strong></p>
                                <p class="ptb5 lh24"><?php echo $comment['content'];?> </p>
                                <?php if(!Yii::app()->user->isGuest && (Yii::app()->user->id == $shop['uid'] || Yii::app()->user->flag == 3)):?><p class="tr"><a href="javascript:void(0);" onclick="$(this).parent().next().show();"><span class="icon-comment"></span> 回复</a></p>							
                                  <div class="pt15 none">
                                  	<form action="/index.php?r=shopComment/replay" method="post" class="form-inline">
                                    	<input type="hidden" name="parent_id" value="<?php echo $comment['id'];?>" />
                                        <input type="text" name="content" value="" class="input-xxlarge"  onkeyup="return enterKeyEventHandler(event,(function(obj) {return function(){replayCommentAction(obj)}})($(this).next()));" required/>
                                        <button class="btn btn-danger" type="button" onclick="replayCommentAction($(this));" autocomplete="off" data-loading-text="请稍候...">提交</button>
                                    </form>
                                  </div>
                              <?php endif;?>                                      
                            </div>
                 			                      
                        </dd>
 						<?php if($comment['replay_list'] && is_array($comment['replay_list'])):?>
                          	<?php foreach($comment['replay_list'] as $comment):?>
                              <!--/回复-->
                              <dd class="w90p fr">
                                  <div class="c_repeat pr">
                                    <div class="bg_f bd6 p10">
                                          <span class="tip_arrow_dt pa"></span>
                                          <span class="tip_arrow_wt pa"></span>
                                          <p class="lh24"><?php echo $comment['content'];?></p>
                                          <p class="ptb5"><span class="o3 pr5"><?php echo $comment['username'];?></span><span class="g9 pr10">发表时间：<?php echo date('Y-m-d H:i',$comment['timestamp']);?></span></p>
                                      </div>                          
                                  </div>
                              </dd>                          
                              <?php endforeach;?>
                          <?php endif;?>                        
                    </dl>
                    	<?php endforeach;?>
                  <?php else:?>
                       <div class="tc mtb20 f16" id="empty-comment-tip">该餐店暂时还没人留言</div>                  
                  <?php endif;?>
                    <?php $this->widget('BootstrapPaging',array('page'=>$page,'total_page' => $total_page,'friendly' => true,'url' => '/shop_comment_'.$shop['shop_id'].'.html')); ?>
                  <!--/ 分页-->      
  <?php if (!Yii::app()->user->isGuest && $show) :?>
                    <form method="post" action="/index.php?r=shopComment/create" class="form_message" id="form-shopComment">
                    <input type="hidden" name="shop_id" value="<?php echo $shop['shop_id'];?>" />
                        <fieldset>
                            <label class="g3 pl5 fm_tit">我要留言</label>
                            <p><textarea cols="5" rows="5" class="w90p" name="content"></textarea></p>
                            <input type="button" class="btn_red" value="提交" autocomplete="off" data-loading-text="请稍候..."  onclick="createCommentAction(this);"/>
                        </fieldset>
                    </form>
                    <?php endif;?> 
                    <!--/留言表单-->                                                
                </div>                        
            </div>                        
            <!--/ 留言列表-->                       
  		</div>
	</div>
</div>  
<script type="text/javascript">
function createCommentAction(obj)
{
	if(loginPopoverFilter('您必须登录后才能进行该操作喔')) {
		if($.trim($('#form-shopComment').find('textarea').val()) == '') {
			addFormTip($('#form-shopComment'),'请填写留言');
			return false;
		}
		$(obj).button('loading');
		$.post($('#form-shopComment').attr('action'),$('#form-shopComment').serialize(),function(data){
			$(obj).button('reset');
			var style = 'alert-error';
			if(data.success) {
				style = 'alert-success';
				document.getElementById('form-shopComment').reset();
				$("#empty-comment-tip").remove();
				$(".ms_comment_lst").prepend(data.content);
			}
			addFormTip($('#form-shopComment'),data.info,style);
		});
	}
}

function replayCommentAction(obj) {
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

$('#want-to-comment').click(function() {
	if(loginPopoverFilter('您必须登录后才能进行该操作喔')) {
		<?php if($show):?>
		$("#form-shopComment").find('textarea').focus();
		<?php else:?>
		modalAlert('提示','只有当天预订过美食才能够留言');
		<?php endif;?>		
	}
});
</script>