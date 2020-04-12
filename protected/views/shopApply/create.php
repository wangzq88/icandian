<?php
$url = '/index.php?r=shopApply/create';
if($model) 
	$url = '/index.php?r=shopApply/update';
?>
<style type="text/css">
#apply-shop-form{position: relative;border:1px solid #e5e5e5;border-radius: 10px;padding:30px 0; display:block;clear: both;}
#apply-shop-form:after {
content: "填写餐店信息";
position: absolute;
top: -1px;
left: -1px;
padding: 5px 10px;
font-size: 14px;
background-color: #f5f5f5;
border: 1px solid #ddd;
color: #9da0a4;
-webkit-border-radius: 4px 0 4px 0;
-moz-border-radius: 4px 0 4px 0;
border-radius: 4px 0 4px 0;
}
.shop-process span{color: #9da0a4;}
.shop-process .processpass{color:#b94a48;}
.pc_main .bd{ padding:40px 102px;}
</style>
<div class="ms_wrap">
	<ul class="breadcrumb w960 mt10">
        <li>
          <a href="/">首页</a> <span class="divider">/</span>
        </li>
        <li class="active">申请开店</li>
    </ul>  
    <div class="p10 bg_opacity clearfix">
   		<div class="pc_main w100p">  
                  <div class="hd bgl_c">
                      <p class="tc f12 shop-process"><span class="plr5 processpass">1. 注册并登录</span>→<span class="plr5 processpass">2. 填写餐店信息 </span>→<span class="plr5">3. 客服审核</span>→<span class="plr5">4. 开店成功</span></p>
                  </div>    
                  <div class="bd">
         
<form class="form-horizontal" id="apply-shop-form" method="post" action="<?php echo $url;?>">
<fieldset>
  <div class="control-group">
    <label class="control-label" for="xing_ming">店主姓名</label>
    <div class="controls">
      <input type="text" id="xing_ming" name="xing_ming" class="input-xlarge required" value="<?php echo isset($model['xing_ming']) ? $model['xing_ming']:'';?>"/>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="mobile">移动电话</label>
    <div class="controls">
      <input type="text" id="mobile" name="mobile" class="input-xlarge required digits" minlength="5" value="<?php echo isset($model['mobile']) ? $model['mobile']:'';?>"/>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="mobile">固定电话</label>
    <div class="controls">
      <input type="text" id="phone" name="phone" minlength="5" class="input-xlarge required digits" value="<?php echo isset($model['phone']) ? $model['phone']:'';?>"/>
    </div>
  </div>  
  <div class="control-group">
    <label class="control-label" for="qq">QQ</label>
    <div class="controls">
      <input type="text" id="qq" name="qq" class="input-xlarge required digits" value="<?php echo isset($model['qq']) ? $model['qq']:'';?>"/>
    </div>
  </div>    
  <div class="control-group">
    <label class="control-label" for="shop_name">餐店名称</label>
    <div class="controls">
      <input type="text" id="shop_name" name="shop_name" class="input-xlarge required" value="<?php echo isset($model['shop_name']) ? $model['shop_name']:'';?>"/>
    </div>
  </div>   
  <div class="control-group">
    <label class="control-label" for="shop_address">餐店地址</label>
    <div class="controls">
      <input type="text" id="shop_address" name="shop_address" class="input-xlarge required" value="<?php echo isset($model['shop_address']) ? $model['shop_address']:'';?>"/>
    </div>
  </div>  
  <div class="control-group">
    <label class="control-label" for="shop_description">餐店描述</label>
    <div class="controls">
      <input type="text" id="shop_description" name="shop_description" class="input-xlarge required" value="<?php echo isset($model['shop_description']) ? $model['shop_description']:'';?>"/>
    </div>
  </div>       
 <div class="control-group">
    <div class="controls">
      <button type="button" class="btn btn-danger" data-loading-text="提交中..." autocomplete="off" onclick="apply_shop_action(this)">立即申请</button>
    </div>
  </div>  
  </fieldset>
</form>
					</div>
		</div>
	</div>
</div>

<script type="text/javascript">
<?php if($model):?>
jQuery(function($) {
	var message = "<?php echo $modle['message'] ? $modle['message']:'审核不通过，请核对您信息的完整性和正确性';?>";
	addFormTip($('#apply-shop-form'),message,'alert-error');
});
<?php endif;?>
function apply_shop_action(obj) {
	if(!$("#apply-shop-form").valid()) {
		return false;
	}
	$(obj).button('loading');
	var form = $(obj).closest("form");
	$.post(form.attr("action"),form.serialize(),function(data) {
		var tip_class = data.success ? 'alert-success':'alert-error';
		addFormTip(form,data.info,tip_class);
		$(obj).button('reset');
		if(data.success > 0) {
			location.href = data.href;
		}
	});
}
jQuery.validator.setDefaults({ 
    errorElement:"span",
	errorClass: "help-inline"
});
</script>