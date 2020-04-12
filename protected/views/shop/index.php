<div class="row">
                <div class="span12">
                    <div class="banner mtb10"><img src="<?php echo $shop['shop_banner'] ? $shop['shop_banner']:'/images/banner/banner_1.jpg';?>" alt="pic" width="960"/></div>
                    <!--/banner-->
                    <div class="ms_wrap">
                         <?php $this->widget('ShopHeader',array('shop'=>$shop)); ?>
                        <!--/ 商家基本信息-->
                        <div class="hd bgl_c mt10">
                            <h3 class="clearfix pl10 pr10">                              
                                <strong class="fb fl">餐店菜单</strong>
                            </h3>
                            <!--/菜单排序-->
                        </div>
                        <div class="bd1 bg_f ptb10 clearfix">
                        	<div class="pl5 pr5">
                            <?php $index = 0;?>
                            <?php if($food_categories && is_array($food_categories)):?>
                                <ul class="nav nav-tabs" id="myTab">
                                <?php foreach($food_categories as $key => $cat):?>
                                	<?php if($cat['status'] > 0):?>
                                  <li <?php if($index == 0):?>class="active"<?php endif;?> title="<?php echo $cat['categories_description'];?>"><a href="#categories_id_<?php echo $cat['categories_id'];?>"><?php echo $cat['categories_name'];?></a></li>
                                  	   <?php $index++;?>
                                   <?php endif;?>
                                <?php endforeach;?>
                                </ul>
                            <?php endif;?>
                            <?php $index = 0;?>
                                <div class="tab-content" style="overflow:visible;">
                                 <?php if($food_categories && is_array($food_categories)):?>
                                 	 <?php foreach($food_categories as $key => $cat):?>
                                     	<?php if($cat['status'] > 0):?>
                                  <div class="tab-pane <?php if($key == 0):?>active<?php endif;?>" id="categories_id_<?php echo $cat['categories_id'];?>">
                                  	<?php if($cat['food_count'] > FOOD_PAGE_COUNT):?>
                                  		 <?php include '_carousel.php';?>									
                                    <?php else:?>
                                    	 <?php include '_thumbnails.php';?>			
                                    <?php endif;?>
                                  </div>
                                  		 <?php $index++;?>
                                  	<?php endif;?>
                                  	<?php endforeach;?>
                                <?php else:?>
                                <div class="tc mtb20 f16">该餐店暂时还没录入菜单</div>
                                <?php endif;?>  
                                </div>                            
                            </div>   
                                                 	
                        </div>                        
                        
                            <?php $this->widget('ShopCart'); ?>
                 
                    </div>

                  
                </div>
            </div>
<script type="text/javascript">
function checkTime(i)
{
	if(i < 10) {
		i = "0" + i;
	}
	return i;
}
function isShow(food) {
	var week = new Date().getDay();//返回值是 0（周日） 到 6（周六） 之间的一个整数。
	week = week == 0 ? 7:week;
	var show = false;//显示预订按钮,默认为否
	switch(food.flag) {
		case 1:
			show = true;
			break;
		case 2:
			var attribs_list = food.attribs.split(',');
			for(var j = 0;j < attribs_list.length; j++) {
				if(attribs_list[j] == week) {
					show = true;
				}
			}
			break;
		case 3:
			if (food.attribs) {
				var tmp = food.attribs.split('-');
				var date = new Date().getDate();
				if (date >= tmp[0] && date <= tmp[1])
					show = true;
			}
			break;
	}
	show = food.is_book > 0 ? show:false;
	return show;	
}

<?php if($shop['flag']):?>
var food_list = new Array(<?php echo count($food_list);?>);
<?php
	foreach($food_list as $key => $food) {
?>
		food_list[<?php echo $key;?>] = {'food_id':<?php echo $food['food_id'];?>,'flag':<?php echo intval($food['flag']);?>,'attribs':'<?php echo $food['attribs'];?>','is_book':<?php echo intval($food['is_book']);?>,'is_package':'<?php echo intval($food['is_package']);?>','food_price':<?php echo $food['food_price'];?>,'food_ids':'<?php echo $food['food_ids'];?>'};
<?php		
	}
?>
function checkCronAction() {
	var shop = {"shop_opening_hours":"<?php echo $shop['row_open_hours'];?>","flag":<?php echo $shop['flag'];?>,"ordering_time":'<?php echo $shop['row_ordering_time'];?>'};
	var today = new Date();
	var h = today.getHours();
	var m = today.getMinutes();
	// add a zero in front of numbers<10
	h = checkTime(h);
	m = checkTime(m);
	now = h + ":" + m;
	//上午和下午的营业时间
	list = shop.shop_opening_hours.split(' ');
	//上午的营业时间段
	var tmp = list[0].split('-');
	//下午的营业时间
	var tmp1 = list[1].split('-');
	//上午和下午订餐时间
	ordering_time = shop.ordering_time.split(' ');
	//显示现在订餐时间
	if (now <= tmp[1]) {//如果现在处于上午的营业时间，显示上午的订餐时间
		now_ordering_time = ordering_time[0];
	} else {
		now_ordering_time = ordering_time[1];
	}
	var show = false;
	for(var i = 0;i < food_list.length; i++) 
	{
		//处于上午营业时间段和处于下午营业时间段
		if((now >= tmp[0] && now < tmp[1]) || (now >= tmp1[0] && now < tmp1[1])) 
		{
			if(food_list[i].is_package == 0) 
			{
				show = isShow(food_list[i]);
			} 
			else 
			{
				var food_ids = food_list[i].food_ids.split(',');
				var text = '';
				for(var j = 0;j < food_ids.length; j++) 
				{
					for(var k = 0;k < food_list.length; k++) 
					{
						if (food_ids[j] == food_list[k].food_id) 
						{
							show = show == false ? false:isShow(food_list[k]);
							text = show ? '供应：有提供':'供应：没提供';
							break;
						}
					}					
				}
				$('.attribs_text_'+food_list[i].food_id+"_"+food_list[i].is_package).text(text);				
			}								
		}
		else 
		{
			show = false;
		}
		if(show) 
		{
			$("#food_id_"+food_list[i].food_id+"_"+food_list[i].is_package).removeClass("disabled yu-ding-disabled yu-ding").addClass("yu-ding");
			$("#food_id_"+food_list[i].food_id+"_"+food_list[i].is_package).tooltip('destroy');			
		} 
		else 
		{
			$("#food_id_"+food_list[i].food_id+"_"+food_list[i].is_package).removeClass("disabled yu-ding").addClass("disabled");
			$("#food_id_"+food_list[i].food_id+"_"+food_list[i].is_package).tooltip('destroy');
			$("#food_id_"+food_list[i].food_id+"_"+food_list[i].is_package).tooltip({title:'此时间不接受订餐',placement:'top'});
		}				
	}
					
}
setInterval('checkCronAction()',60000);
checkCronAction();
<?php endif;?>
$('.carousel').carousel({
  interval:false
});

$('#myTab a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})

jQuery(function($) {
	$(".img-polaroid").tooltip({placement:'top'});
	$("body").delegate(".yu-ding","click",function() {
		var food_id = $(this).attr('food_id');
		var is_hot = $(this).attr('is_hot');
		var is_package = $(this).attr('is_package');
		var food_name = $(this).parent().prevAll('h5').text();
		var food_price = $(this).attr('food_price');
		var shop_id = '<?php echo $shop['shop_id'];?>';
		var shop_name = '<?php echo $shop['shop_name'];?>';
		var uid = identity_flag;
		var food_item = new Shopping(0,food_id,food_name,1,is_hot,is_package,shop_id,food_price,shop_name,uid,0);
		addItemAction(food_item);
	});
});
</script>