<div class="dinner_car span4">
                                <div class="hd bgl_c">
                                    <h3 class="clearfix plr10">
                                    <span class="btn-group fl mt8_ie7"><a class="btn btn-small" id="resize_shop_car"><span class="icon-resize-horizontal"></span></a><a class="btn btn-small" onclick="refreshCartAction(identity_flag)"><span class="icon-refresh"></span></a><a class="btn btn-small" onclick="deleteShopFoodAction(this,identity_flag)"><span class="icon-trash"></span></a></span>
                                    <strong class="fb fr"><span class="icon-shopping-cart"></span>购餐车</strong>
                                    </h3>
                                </div>
                                <div class="bd bd2 scrollbar" style="max-height:550px;overflow-y:scroll;">
                                	<div class="p10" id="shopping-cart-item">
                                        <p class="tr mt10"><a href="javascript:void(0);" class="btn btn-small" onclick="if(loginPopoverFilter('您必须登录后才能进入结算喔')) location.href='/index.php?r=user/checking';"><span class="icon-tasks"></span> 结算</a></p>                                        
                                    </div>
                                </div>                        
                            </div>
<script type="text/javascript">
$("#resize_shop_car").toggle(
	function() {
		$("div.dinner_car").animate({
			right:0	
		},500)			
	},
	function() {
		$("div.dinner_car").animate({
			right:"-15%"	
		},500)			
	}	
);      
</script>                      