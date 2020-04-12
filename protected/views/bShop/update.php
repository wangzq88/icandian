<?php $timestamp = time();?>
<style type="text/css">
#thumbnails,banner-thumbnails{float:left;margin-right:20px;}
#thumbnails,banner-thumbnails {background: #ddd;padding: 0.8em;border: solid 2px #fff;}
table td{ padding:5px 0;}
tr td:first-child{text-align:right; padding-right:10px; font-weight:bold;}
tr td:first-child a{cursor: help;border-bottom: 1px dotted #999999;text-decoration:none; color:inherit;}
textarea{padding:2px;}
textarea:focus{border:1px solid #09F; padding:2px;}
</style>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/uploadify/uploadify.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/uploadify/jquery.uploadify.min.js"></script>  
<link rel="stylesheet" type="text/css" href="/assets/jquery-imgareaselect/css/imgareaselect-default.css" />
<script type="text/javascript" src="/assets/jquery-imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>
<div id="update-view" class="easyui-panel" title="基本信息 / 编辑餐店">
     <div style="padding:10px 0 10px 60px;">  
 <form id="ff" method="post" action="/index.php?r=bShop/update" enctype="multipart/form-data">  

            <table>  
                <tr>  
                    <td><a href="javascript:void(0);" title="餐店的名称只有申请才能更改" class="easyui-tooltip">餐店名称</a>：</td>  
                    <td><?php echo $shop['shop_name'];?>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="applyShopNameView();">申请</a></td>  
                </tr>
<tr>  
                    <td><a href="javascript:void(0);" title="餐店的地址需要申请，才能修改" class="easyui-tooltip">餐店地址</a>：</td>  
                    <td><?php echo $shop['shop_address'];?>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="applyShopAddressView();">申请</a></td>
                    </tr>                  
 <tr>  
                    <td><a href="javascript:void(0);" title="如在某些节日（春节），要暂停营业，可以将其关闭" class="easyui-tooltip">餐店状态</a>：</td>  
                    <td> 
                    	<select name="flag" style="width:200px;" id="flag">
                        	<option value="1">开张</option>
                            <option value="0" <?php if($shop['flag'] == 0):?> selected="selected"<?php endif;?>>关闭</option>
                        </select>
                    </td>  
                </tr>                  
                <tr>  
                    <td><a href="javascript:void(0);" title="设置你餐店的主要菜系，可以让用户根据自己的爱好搜索到你。如果不清楚，可以设置为 不限" class="easyui-tooltip">餐店菜系</a>：</td>  
                    <td> 
                    	<select name="shop_cuisine" style="width:200px;" id="shop_cuisine">
                        	<option value="0">不限</option>
                            <?php if($cuisines && is_array($cuisines)):?>
                            	<?php foreach($cuisines as $cuisine):?>
                                	<option value="<?php echo $cuisine['cuisine_id'];?>" <?php if($shop['shop_cuisine'] == $cuisine['cuisine_id']): ?>selected="selected" <?php endif;?>><?php echo $cuisine['cuisine_name'];?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </td>  
                </tr>                            
                <tr>  
                    <td><a href="javascript:void(0);" title="介绍您的餐店" class="easyui-tooltip">餐店介绍</a>：</td>  
                    <td><textarea name="shop_description" cols="50" rows="5"><?php echo $shop['shop_description'];?></textarea></td>  
                </tr>
                <tr>  
                    <td><a href="javascript:void(0);" title="提醒用户关于订餐的信息" class="easyui-tooltip">温馨提示</a>：</td>  
                    <td><textarea name="shop_tips" cols="50" rows="3"><?php if($shop['shop_tips']): echo $shop['shop_tips']; else: ?>为了方便您按时就餐，请提早一小时订餐<?php endif;?></textarea></td>  
                </tr>                 
                <tr>  
                    <td><a href="javascript:void(0);" title="设置订餐的注意事项，也可以是关于优惠的信息" class="easyui-tooltip">公告</a>：</td>  
                    <td><textarea name="shop_announcement" cols="50" rows="5"><?php if($shop['shop_announcement']):echo $shop['shop_announcement'];else:?>1.订餐满20元起送
2.价格、品种如有变动，以餐厅当日价目为准
3.送餐范围为步行10分钟之内
4.请于收到食品1小时内食用<?php endif;?></textarea></td>  
                </tr> 
                <tr>  
                    <td><a href="javascript:void(0);" title="设置你餐店的标识图片，让你的餐店个性化，以便用户记住你的餐店" class="easyui-tooltip">餐店 Logo</a>：</td>  
                    <td><?php if($shop['shop_logo']): ?><img src="<?php echo $shop['shop_logo'];?>" alt="Logo"  width="48" height="48" title="默认Logo" id="shop-logo"/><?php else:?><img src="/images/shop/shop.png" alt="Logo"  width="48" height="48" id="shop-logo"/><?php endif;?>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="$('#update-logo-view').dialog('open');">修改</a></td>  
                </tr>                      
                <tr>
                	<td><a href="javascript:void(0);" title="广告牌可以宣传你的店铺" class="easyui-tooltip">餐店广告牌</a>：</td>
                    <td><?php if($shop['shop_banner']): ?><img src="<?php echo $shop['shop_banner'];?>" alt="Banner"  width="370" height="80" title="默认Logo" id="shop-banner"/><?php else:?><img src="/images/banner/banner_1.jpg" alt="Banner"  width="370" height="80" id="shop-banner"/><?php endif;?>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="$('#update-ad-view').dialog('open');">修改</a></td>
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
                    <label for="ss<?php echo $key;?>0">上午：</label><input id="ss-<?php echo $key;?>0" class="easyui-timespinner"  style="width:80px;" required="required" data-options="min:'00:00',max:'12:00',showSeconds:false" value="<?php echo $item[0];?>" name="shop_opening_hours[<?php echo $key;?>][0]"/>—<input id="ss-<?php echo $key;?>1" class="easyui-timespinner"  style="width:80px;" required="required" data-options="min:'00:00',max:'18:00',showSeconds:false" value="<?php echo $item[1];?>" name="shop_opening_hours[<?php echo $key;?>][1]"/>
                    <label for="yy<?php echo $key;?>0">下午：</label>
                    <input id="yy<?php echo $key;?>0" class="easyui-timespinner"  style="width:80px;" required="required" data-options="min:'12:00',max:'24:00',showSeconds:false" value="<?php echo $item[2];?>" name="shop_opening_hours[<?php echo $key;?>][2]"/>—
                    <input id="yy<?php echo $key;?>1" class="easyui-timespinner"  style="width:80px;" required="required" data-options="min:'12:00',max:'24:00',showSeconds:false" value="<?php echo $item[3];?>" name="shop_opening_hours[<?php echo $key;?>][3]"/>
                    </td>  
                </tr>  
                <?php endforeach;?>
                <?php else:?>
  			<?php for($i = 1; $i <= 7; $i++) :?>
                <?php 
					switch($i) {
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
                	<?php if ($i == 1) :?>
                    <td><a href="javascript:void(0);" title="设置营业的时间段，在营业期间，用户还是可以订餐的。如果那天暂停营业，则可以将其设置为：00:00-00:00 12:00-12:00 。只要前后两个时间段设置相同即可" class="easyui-tooltip">餐店营业时间</a>：</td> 
                    <?php else:?> 
                    <td></td> 
                    <?php endif;?>
                    <td><!--<select name="opening_day[]"><option value=""></option></select>--><?php echo $day_text;?>&nbsp;&nbsp;
                    <label for="ss<?php echo $i;?>0">上午：</label><input id="ss-<?php echo $i;?>0" class="easyui-timespinner"  style="width:80px;" required="required" data-options="min:'00:00',max:'12:00',showSeconds:false" value="10:30" name="shop_opening_hours[<?php echo $i;?>][0]"/>—<input id="ss-<?php echo $i;?>1" class="easyui-timespinner"  style="width:80px;" required="required" data-options="min:'00:00',max:'18:00',showSeconds:false" value="14:00" name="shop_opening_hours[<?php echo $i;?>][1]"/>
                    <label for="yy<?php echo $i;?>0">下午：</label>
                    <input id="yy<?php echo $i;?>0" class="easyui-timespinner"  style="width:80px;" required="required" data-options="min:'12:00',max:'24:00',showSeconds:false" value="16:00" name="shop_opening_hours[<?php echo $i;?>][2]"/>—
                    <input id="yy<?php echo $i;?>1" class="easyui-timespinner"  style="width:80px;" required="required" data-options="min:'12:00',max:'24:00',showSeconds:false" value="20:30" name="shop_opening_hours[<?php echo $i;?>][3]"/>
                    </td>  
                </tr>               
                <?php endfor;?>   
                <?php endif;?>                                     
                <tr>
                <td><a href="javascript:void(0);" title="提示用户哪个时间段最适宜订餐。请注意：只要你的店铺还处在营业时间段，用户还是可以订餐的" class="easyui-tooltip">中餐最佳订餐时间</a>：</td> 
                <td>
                <input id="aa-00" class="easyui-timespinner"  style="width:80px;" required="required" data-options="min:'00:00',max:'12:00',showSeconds:false" value="<?php echo $shop['ordering_time'][0] ? $shop['ordering_time'][0]:'10:00';?>" name="ordering_time[]"/> 
                ——
                <input id="aa-01" class="easyui-timespinner"  style="width:80px;" required="required" data-options="min:'00:00',max:'12:00',showSeconds:false" value="<?php echo $shop['ordering_time'][1] ? $shop['ordering_time'][1]:'11:00';?>" name="ordering_time[]"/>   
				</td>
                </tr>  
                <td><a href="javascript:void(0);" title="提示用户哪个时间段最适宜订餐，如果不接受订餐，请设置为：00:00 -- 00:00 ，只要前后时间设置为同样的值即可。请注意：只要你的店铺还处在营业时间段，用户还是可以订餐的" class="easyui-tooltip">晚餐最佳订餐时间</a>：</td> 
                <td>
                    <input id="bb00" class="easyui-timespinner"  style="width:80px;" required="required" data-options="min:'12:00',max:'24:00',showSeconds:false" value="<?php echo $shop['ordering_time'][2] ? $shop['ordering_time'][2]:'17:00';?>" name="ordering_time[]"/>
                    ——
                    <input id="bb01" class="easyui-timespinner"  style="width:80px;" required="required" data-options="min:'12:00',max:'24:00',showSeconds:false" value="<?php echo $shop['ordering_time'][3] ? $shop['ordering_time'][3]:'18:00';?>" name="ordering_time[]"/></td>
                </tr>                                                                          
               </table>
               </form>
</div>
<div style="background:#fafafa;text-align:center;padding:5px">  
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm()">保存</a>  
        </div>  
 </div> 
<script type="text/javascript">
var ias;
</script>
 <!--申请修改餐店名称-->
<?php include '_applyName.php';?>
 <!--申请修改餐店地址-->
<?php include '_applyAddress.php';?>
 <!--修改Logo视图-->
<?php include '_updateLogo.php';?>
 <!--修改横幅视图-->
<?php include '_updateBanner.php';?>
<iframe id="frameFile" name="frameFile" style="display:none"></iframe>        
<script type="text/javascript">  
 
function submitForm(){  
	//$('#ff').form('submit');  
	$('#ff').submit();
}  
		
function submitLogoForm() {
	$('#logo-ff').submit();
}



    </script>          


<script type="text/javascript">

function addNormalImage(id,src,width,height,thumbID) {
	$('#'+id).remove();
	var newImg = document.createElement("img");
//	newImg.style.margin = "5px";
	newImg.style.verticalAlign = "middle";
	newImg.width = width;
	newImg.height = height;
	newImg.id = id;
	var divThumbs = document.getElementById(thumbID);
	divThumbs.insertBefore(newImg, divThumbs.firstChild);
	//document.getElementById("thumbnails").appendChild(newImg);
	if (newImg.filters) {
		try {
			newImg.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 0;
		} catch (e) {
			// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
			newImg.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + 0 + ')';
		}
	} else {
		newImg.style.opacity = 0;
	}

	newImg.onload = function () {
		fadeIn(newImg, 0);
	};
	newImg.src = src;	
}

function addPreviewImage(src,id) {
	var newImg = document.createElement("img");	
	var divThumbs = document.getElementById(id);
	$('#'+id).empty();
	divThumbs.insertBefore(newImg, divThumbs.firstChild);
	//document.getElementById("thumbnails").appendChild(newImg);
	if (newImg.filters) {
		try {
			newImg.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 0;
		} catch (e) {
			// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
			newImg.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + 0 + ')';
		}
	} else {
		newImg.style.opacity = 0;
	}

	newImg.onload = function () {
		fadeIn(newImg, 0);
	};
	newImg.src = src;		
}

function addImage(src) {
	var newImg = document.createElement("img");
	newImg.style.margin = "5px";
	newImg.style.verticalAlign = "middle";

	var divThumbs = document.getElementById("thumbnails");
	divThumbs.insertBefore(newImg, divThumbs.firstChild);
	//document.getElementById("thumbnails").appendChild(newImg);
	if (newImg.filters) {
		try {
			newImg.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 0;
		} catch (e) {
			// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
			newImg.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + 0 + ')';
		}
	} else {
		newImg.style.opacity = 0;
	}

	newImg.onload = function () {
		fadeIn(newImg, 0);
	};
	newImg.src = src;
}

function fadeIn(element, opacity) {
	var reduceOpacityBy = 5;
	var rate = 30;	// 15 fps

	if (opacity < 100) {
		opacity += reduceOpacityBy;
		if (opacity > 100) {
			opacity = 100;
		}

		if (element.filters) {
			try {
				element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
			}
		} else {
			element.style.opacity = opacity / 100;
		}
	}

	if (opacity < 100) {
		setTimeout(function () {
			fadeIn(element, opacity);
		}, rate);
	}
}	
</script>