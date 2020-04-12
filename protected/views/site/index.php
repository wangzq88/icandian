     <div class="row">
                <div class="span12">
                    <div class="banner mtb10"><img src="/images/banner/banner_1.jpg" alt="pic" /></div>
                    <!--/banner-->
                    <div class="assort_lst">
                        <div class="controls clearfix">
                          <div class="input-append fl">
    						<form action="/index.php" method="get" id="search_top_form">
                            <input type="text" size="16" id="search_top" name="shop_name" class="span4" data-provide="typeahead" placeholder="搜索餐厅" value="<?php echo $_GET['shop_name'];?>"/><span class="add-on"><i class="icon-search"></i></span>
                            </form>
                          </div>
                          <!--/搜索餐厅 -->
                          <dl class="assort_step fr">
                            <dd><a href="javascript:void(0);">选餐店</a><i class="icon-chevron-right"></i></dd>
                            <dd><a href="javascript:void(0);">选美食</a><i class="icon-chevron-right"></i></dd>
                            <dd><a href="javascript:void(0);">下订单</a><i class="icon-chevron-right"></i></dd>
                            <dd><a href="javascript:void(0);">餐到付款</a></dd>
                          </dl>
                          <!--/订餐步骤 -->
                          <dl class="assort_lst_a pt15 clearfix">
                            <dt class="assort_lst_tit">菜&nbsp;系<span class="tip_arrow_br pa"></span></dt>
                            <dd>
                                <a href="javascript:void(0);" class="active cuisine-list" cuisine="0">不限</a>
                             <?php foreach($cuisines as $cuisine) :?>
                                <a href="javascript:void(0);" class="cuisine-list" cuisine="<?php echo $cuisine['cuisine_id'];?>"><?php echo $cuisine['cuisine_name'];?></a>
                             <?php endforeach;?>                    
                            </dd>
                          </dl>
                          <dl class="assort_lst_a pt15 clearfix">
                            <dt class="assort_lst_tit">地&nbsp;点<span class="tip_arrow_br pa"></span></dt>
                            <dd>
                            <?php foreach($region_list as $key => $region) :?>
                            	<?php if ($key == 0):?>
                                	<a href="javascript:void(0);" class="active region-list" index="<?php echo $key;?>" regionid="<?php echo $region['region_id'];?>"><?php echo $region['region_name'];?></a>
                                <?php else:?>
                                	<a href="javascript:void(0);" class="region-list" index="<?php echo $key;?>" regionid="<?php echo $region['region_id'];?>"><?php echo $region['region_name'];?></a>
                                <?php endif;?>
                            <?php endforeach;?>                     
                            </dd>
                          </dl>  
                           <?php $first_area = 0;$i = 0;?>
                           <?php foreach($region_list as $key =>$region) :?>
                          <div class="hover_area_a mt10 fr" style="width:90%;<?php echo $key != 0 ? 'display:none;':'';?>" regionid="<?php echo $region['region_id']; ?>">
                            <span class="tip_arrow_rt pa" style="left:2%;top:-12px;"></span>
                            <div class="shangquan">
                            	<?php foreach($area_list as $regionKey => $areas) :?>
                                	<?php if($region['region_id'] == $regionKey): ?>
                                    	<?php foreach($areas as  $area) :?>
                                         <?php $first_area = $i == 0 ? $area['area_id'] : $first_area;?>
                                            <a class="area-list <?php echo $i == 0 ? 'active':''?>" href="javascript:void(0);" areaid="<?php echo $area['area_id'];?>" regionid="<?php echo $region['region_id'];?>">
                                                <?php echo $area['area_name'];?>
                                            </a>
                                           <?php $i++;?>  
                                        <?php endforeach;?>  
                                	<?php endif;?>
                                <?php endforeach;?>  
                            </div>							                          	
                          </div>  
                           <?php endforeach;?>    
                           <?php $i = 0;?>
                           <?php foreach($area_list as $key =>$areas) :?>
                           		<?php foreach($areas as  $area) :?>
                           <div class="hover_area_b mt10 fr" style="width:90%;<?php echo $area['area_id'] != $first_area ? 'display:none;':'';?>" areaid="<?php echo $area['area_id']; ?>">
                            <span class="tip_arrow_yt pa" style="left:2%;top:-12px;"></span>
                            <div class="shangquan_sub">                     
                                 <a class="section-list <?php if($i == 0):?>active<?php endif;?>" href="/area_<?php 
								 	echo $area['area_id']; 
									echo $_GET['cuisine'] ? '_cuisine_'.$_GET['cuisine']:'';
								?>.html" areaid="<?php echo $area['area_id']; ?>" sectionid="0">不限</a>   
                                 <?php $i++;?>                     
								<?php foreach($section_list as $sectionKey => $sections) :?>
                                	<?php if($area['area_id'] == $sectionKey): ?>
                                    	<?php foreach($sections as  $section) :?>    
                                            <a class="section-list" href="/section_<?php 
											echo $section['section_id']; 
											echo $_GET['cuisine'] ? '_cuisine_'.$_GET['cuisine']:'';
											?>.html" areaid="<?php echo $area['area_id']; ?>" sectionid="<?php echo $section['section_id']; ?>">
                                                 <?php echo $section['section_name'];?>
                                            </a>
                                         <?php endforeach;?>  
                                	<?php endif;?>
                                <?php endforeach;?>                                
                            </div>							                          	
                          </div>
                          		 <?php endforeach;?>    
                          <?php endforeach;?>                                        
                        </div>
                        <!--/分类筛选-->
                    </div>
                    <!--/.assort_lst-->
                    <div class="index_wrap mt10">
                    	<div class="hd bgl_c pl10">
                            <div class="row">
                                <div class="span1"><strong class="fb">选餐店</strong></div>
                            </div>
                        </div>
                        <div class="bd clearfix">
                        <?php if($shop_list && is_array($shop_list)):?>
                        	<ul class="store_lst_wrap">
                            	<li class="store_lst clearfix">
                                  	<?php foreach($shop_list as $key => $shop):?>
                                    <dl class="store_lst_detail clearfix pr">
                                        <dt class="store_lst_pic <?php if($shop['flag'] == 0):?>close_state<?php endif;?>"><a href="<?php echo $this->createUrl('shop/index',array('id'=>$shop['shop_id']));?>"><img src="<?php echo $shop['shop_logo'] ? $shop['shop_logo']:SHOP_DEFAULT_LOGO;?>" /></a></dt>
                                        <dd class="store_lst_info">
                                            <h4 class="f14"><a href="<?php echo $this->createUrl('shop/index',array('id'=>$shop['shop_id']));?>"><?php echo $shop['shop_name'];?></a></h4>
                                            <p>菜系：<span><?php echo $shop['shop_cuisine'];?></span></p>
                                            <p>营业时间：<span><?php echo $shop['now_opening_hours'];?></span></p>
                                            <p id="shop-brief-info-<?php echo $shop['shop_id'];?>"><?php if(!$shop['open']):?><span class="label mr5">休息中</span><?php else:?><?php if($shop['coupon']):?><span class="label label-important mr5">卷</span><?php endif;?><?php if($shop['new']):?><span class="label label-info">新</span><?php elseif(!$shop['coupon']):?>订餐时间：<span><?php echo $shop['now_ordering_time'];?></span><?php endif;?><?php endif;?></p>
                                            <p><!--起送价：<span class="r6">10</span>--> 美食数目：<span class="r6"><?php echo $shop['food_count'] > 0 ? $shop['food_count'] :'未知';?></span> </p>
                                        </dd>
                                        <dd class="store_tip_info p10 <?php echo ($key+1)%3 == 0 ? 'store_tip_r':'store_tip_l' ?>" style="display:none">
                                        	<span class="tip_arrow_yl pa"></span>
                                            <span class="tip_arrow_yl2 pa"></span>
											<dl class="tip_ct">
                                            	<dt class="tip_hd clearfix">
                                                    <div class="fl w20p"><img src="<?php echo $shop['shop_logo'] ? $shop['shop_logo']:SHOP_DEFAULT_LOGO;?>" class="small_pic"/></div>
                                                    <div class="fr w75p">
                                                        <h4 class="f14 pt5"><a href="<?php echo $this->createUrl('shop/index',array('id'=>$shop['shop_id']));?>"><?php echo $shop['shop_name'];?></a></h4>
                                                        <p>菜系：<span><?php echo $shop['shop_cuisine'];?></span></p>
                                                    </div>                                                
                                                </dt>
												<dd class="tip_bd">
                                                	<p class="r6"><strong>温馨提示：</strong><?php echo strip_tags($shop['shop_tips']);?></p>
<!--                                                    <p><strong>配送范围：</strong>到金网电脑商城 30元。</p>
                                                    <p><strong>起送价：</strong> 30元。</p>-->
                                                    <p><strong>营业时间：</strong><?php echo $shop['shop_opening_hours'];?></p>
                                                    <p><strong>最佳订餐：</strong><?php echo $shop['ordering_time'];?></p>
                                                    <p><strong>地址信息：</strong><?php echo $shop['shop_address'];?>
</p>												<p><strong>公告：</strong><?php echo strip_tags($shop['shop_announcement']);?></p>
                                                </dd>
                                            </dl>
                                                                                      
                                        </dd>
                                    </dl>
                                    <?php endforeach;?>
                                </li>
                            </ul>
                       <?php else:?>
                       		<div class="assort_lst tc f16" style="padding:30px 0;">没有相关的餐店</div>
                       <?php endif;?>                            
                        </div>
                        <?php 
							$url = '/index.html';
                        	if(isset($_GET['area']) && $_GET['area'] > 0):
                        		$url = '/area_'.$_GET['area'].'.html';
                            elseif(isset($_GET['section']) && $_GET['section'] > 0):
                        	 	$url = '/section_'.$_GET['section'].'.html';
							endif;
                        	if(isset($_GET['cuisine'])):
                       			$pos = strrpos($url, ".");
								if ($pos !== false) {
									$sub = substr($url,0,$pos);
									$url = $sub.'_cuisine_'.$_GET['cuisine'].'.html';
								}
					   		endif;
						    $this->widget('BootstrapPaging',array('page'=>$page,'total_page' => $total_page,'friendly' => true,'url' =>$url)); 
					    ?>
                    </div>
                </div>
            </div>
<?php $this->renderDynamic('renderDynamicTop'); ?>
<script type="text/javascript">
var shop_list = new Array(<?php echo count($shop_list);?>);
<?php
	foreach($shop_list as $key => $shop) {
?>
		shop_list[<?php echo $key;?>] = {'shop_id':<?php echo $shop['shop_id'];?>,'flag':<?php echo intval($shop['flag']);?>,'coupon':<?php echo $shop['coupon'];?>,'new':<?php echo intval($shop['new']);?>,'ordering_time':'<?php echo $shop['ordering_time'];?>','shop_opening_hours':'<?php echo $shop['shop_row_opening'];?>'};
<?php		
	}
?>
function checkTime(i)
{
	if(i < 10) {
		i = "0" + i;
	}
	return i;
}
function checkCronAction() 
{
	var list;
	var today = new Date();
	var h = today.getHours();
	var m = today.getMinutes();
	// add a zero in front of numbers<10
	h = checkTime(h);
	m = checkTime(m);
	now = h + ":" + m;
	var ordering_time;
	for(var i = 0;i < shop_list.length; i++) {
		var html = '';
		if(shop_list[i].flag > 0) {
			//上午和下午的营业时间
			list = shop_list[i].shop_opening_hours.split(' ');
			//上午的营业时间段
			var tmp = list[0].split('-');
			//下午的营业时间
			var tmp1 = list[1].split('-');
			//上午和下午订餐时间
			ordering_time = shop_list[i].ordering_time.split(' ');
			//显示现在订餐时间
			if (now <= tmp[1]) {//如果现在处于上午的营业时间，显示上午的订餐时间
				now_ordering_time = ordering_time[0];
			} else {
				now_ordering_time = ordering_time[1];
			}
			//处于上午营业时间段
			if(now >= tmp[0] && now < tmp[1]) {
				if(shop_list[i].coupon > 0) {
					html += '<span class="label label-important mr5">卷</span>';
				}
				if(shop_list[i].new > 0) {
					html += '<span class="label label-info">新</span>';
				}
				if(shop_list[i].coupon == 0 && shop_list[i].new == 0) {
					html += '订餐时间：<span>'+ordering_time[0]+'</span>';
				}
			} else if(now >= tmp1[0] && now < tmp1[1]) {
				if(shop_list[i].coupon > 0) {
					html += '<span class="label label-important mr5">卷</span>';
				}
				if(shop_list[i].new > 0) {
					html += '<span class="label label-info">新</span>';
				}
				if(shop_list[i].coupon == 0 && shop_list[i].new == 0) {
					html += '订餐时间：<span>'+ordering_time[1]+'</span>';
				}				
			} else {
				html = '<span class="label mr5">休息中</span>';
			}
			$("#shop-brief-info-"+shop_list[i].shop_id).html(html);						
		}
	}
}
setInterval('checkCronAction()',1000);
</script>