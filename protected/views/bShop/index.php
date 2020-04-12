<style type="text/css">
table td{ padding:5px 0;}
tr td:first-child{text-align:right; padding-right:10px; font-weight:bold;}
tr td:first-child a{cursor: help;border-bottom: 1px dotted #999999;text-decoration:none; color:inherit;}
</style>
<div style="padding:10px 0 10px 60px;" class="easyui-panel" title="基本信息 / 餐店信息"> 
  <table>  
                <tr>  
                    <td><a href="javascript:void(0);" title="餐店的名称只有申请才能更改" class="easyui-tooltip">餐店名称</a>：</td>  
                    <td><?php echo $shop['shop_name'];?></td>  
                </tr>  
 <tr>  
                    <td><a href="javascript:void(0);" title="餐店的地址需要申请，才能修改" class="easyui-tooltip">餐店地址</a>：</td>  
                    <td><?php echo $shop['shop_address'];?></td>
                    </tr>                   
				 <tr>  
                    <td><a href="javascript:void(0);" title="如在某些节日（春节），要暂停营业，可以将其关闭" class="easyui-tooltip">餐店状态</a>：</td>  
                    <td> 
                    	<?php echo $shop['flag'];?>
                    </td>  
                </tr>  
<tr>  
                    <td><a href="javascript:void(0);" title="设置你餐店的主要菜系，可以让用户根据自己的爱好搜索到你。如果不清楚，可以设置为 不限" class="easyui-tooltip">餐店菜系</a>：</td>  
                    <td> 
                    	<?php echo $shop['shop_cuisine'];?>
                    </td>  
                </tr>                                                               
                <tr>  
                    <td><a href="javascript:void(0);" title="介绍您的餐店" class="easyui-tooltip">餐店介绍</a>：</td>  
                    <td><?php echo nl2br($shop['shop_description']);?></td>  
                </tr>
 <tr>  
                    <td><a href="javascript:void(0);" title="提醒用户关于订餐的信息" class="easyui-tooltip">温馨提示</a>：</td>  
                    <td><?php if($shop['shop_tips']): echo nl2br($shop['shop_tips']); else: ?>为了方便您按时就餐，请提早一小时订餐<?php endif;?></td>  
                </tr>                    
                <tr>  
                    <td><a href="javascript:void(0);" title="设置订餐的注意事项，也可以是关于优惠的信息" class="easyui-tooltip">餐店公告</a>：</td>  
                    <td><?php if($shop['shop_announcement']):echo nl2br($shop['shop_announcement']);else:?>1.订餐满20元起送<br />2.价格、品种如有变动，以餐厅当日价目为准<br />3.请于收到食品1小时内食用<br /><?php endif;?></td>  
                    </tr>
<tr>  
                    <td><a href="javascript:void(0);" title="设置你餐店的标识图片，让你的餐店个性化，以便用户记住你的餐店" class="easyui-tooltip">餐店 Logo</a>：</td>  
                    <td><?php if($shop['shop_logo']): ?><img src="<?php echo $shop['shop_logo'];?>" alt="Logo"  width="48" height="48" title="默认Logo"/><?php else:?><img src="/images/shop/shop.png" alt="Logo"  width="48" height="48" /><?php endif;?></td>  
                </tr>                           
				 <tr>
                	<td><a href="javascript:void(0);" title="广告牌可以宣传你的店铺" class="easyui-tooltip">餐店广告牌</a>：</td>
                    <td><?php if($shop['shop_banner']): ?><img src="<?php echo $shop['shop_banner'];?>" alt="Banner"  width="370" height="80" title="默认广告牌"/><?php else:?><img src="/images/banner/banner_1.jpg" alt="Banner"  width="370" height="80"/><?php endif;?></td>
                </tr>                        
  <?php if($shop['shop_opening_hours'] && is_array($shop['shop_opening_hours'])) :?>
                <?php foreach($shop['shop_opening_hours'] as $key => $item) :?>
                <?php 
					switch($key) {
						case '1':
							$day_text = '星期一';
							break;
						case '2':
							$day_text = '星期二';
							break;
						case '3':
							$day_text = '星期三';
							break;
						case '4':
							$day_text = '星期四';
							break;		
						case '5':
							$day_text = '星期五';
							break;		
						case '6':
							$day_text = '星期六';
							break;		
						case '7':
							$day_text = '星期日';
							break;																																										
					}
				?>
                <tr>  
                	<?php if ($key == 1) :?>
                    <td><a href="javascript:void(0);" title="设置营业的时间段，在营业期间，用户还是可以订餐的。如果那天暂停营业，则可以将其设置为：00:00-00:00 12:00-12:00 。只要前后两个时间段设置相同即可" class="easyui-tooltip">餐店营业时间</a>：</td> 
                    <?php else:?> 
                    <td></td> 
                    <?php endif;?>
                    <td><!--<select name="opening_day[]"><option value=""></option></select>--><?php echo $day_text;?>&nbsp;&nbsp;
                    <label for="ss<?php echo $key;?>0">上午：</label><?php echo $item[0];?>—<?php echo $item[1];?>
                    <label for="yy<?php echo $key;?>0">下午：</label><?php echo $item[2];?>—<?php echo $item[3];?>
                    </td>  
                </tr>  
                <?php endforeach;?>
                <?php endif;?>                                     
                <tr>
                <td><a href="javascript:void(0);" title="提示用户哪个时间段最适宜订餐。请注意：只要你的店铺还处在营业时间段，用户还是可以订餐的" class="easyui-tooltip">中餐最佳订餐时间</a>：</td> 
                <td>
                <?php echo $shop['ordering_time'][0];?>
                ——
                <?php echo $shop['ordering_time'][1];?>  
				</td>
                </tr>  
                <td><a href="javascript:void(0);" title="提示用户哪个时间段最适宜订餐，如果不接受订餐，请设置为：00:00 -- 00:00 ，只要前后时间设置为同样的值即可。请注意：只要你的店铺还处在营业时间段，用户还是可以订餐的" class="easyui-tooltip">晚餐最佳订餐时间</a>：</td> 
                <td>
                    <?php echo $shop['ordering_time'][2];?>
                    ——
                    <?php echo $shop['ordering_time'][3];?></td>
                </tr>                      
                    </table>              
<div style="background:#fafafa;text-align:center;padding:5px">  
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="location.href='/index.php?r=bShop/update';">编辑</a>  
        </div>                     
</div>