<ul class="thumbnails" style="margin-left:0;padding:0 44px; min-height:1011px;">
<?php $i = 0;?>
<?php foreach($food_list as $food):?>
	<?php if($cat['categories_id'] == $food['categories_id']):?>
	<?php
        //计算最左边和最右边的间距
        $style = $i%4 == 0 ? "margin-left:0;":"";
    ?>      
		<li class="w200" style="<?php echo $style;?>">
	<div class="thumbnail">
		<?php if($food['food_img']):?>
		<img src="<?php echo $food['food_img'];?>" alt="" style="width:160px;height:160px;" class="img-polaroid mt5" title="<?php echo $food['food_remark'];?>"/>
		<?php else:?>
		<img src="/images/banner/7792647_m.jpg" alt="" style="width:160px;height:160px;" class="img-polaroid mt5" title="<?php echo $food['food_remark'];?>"/>
		<?php endif;?>
		<h5 class="lh30 fb tc f16" style="overflow:hidden;height:30px;"><?php echo $food['food_name'];?></h5>
                      <?php if($food['is_hot'] || $food['is_new'] || $food['is_facia']):?>                 
		<p class="pl15">标签： <?php if($food['is_new']):?><span class="badge badge-info">新</span>&nbsp;<?php endif;?><?php if($food['is_hot']):?><span class="badge badge-important">辣</span><?php endif;?>
		<?php if($food['is_facia']):?><span class="badge badge-warning">牌</span><?php endif;?>
		</p>
		<?php else:?>
		<p class="pl15">标签： 无</p>
		<?php endif;?>
		<p class="pl15 attribs_text_<?php echo $food['food_id'];?>_<?php echo intval($food['is_package']);?>">供应： <?php echo $food['attribs_text'];?></p>
		<p class="pl15">价格： <span class="Georgia f30 r3"><?php echo $food['food_price'];?></span> 元</p>
		<p class="pl15 pt5 pb5 clearfix"><a class="btn btn-small" onclick="collectionFood(this,'<?php echo $shop['shop_id']?>','<?php echo $shop['shop_name']?>','<?php echo $food['food_id']?>','<?php echo $food['food_name']?>','<?php echo $food['food_price']?>','<?php echo (int)$food['is_package'];?>')"><i class="icon-star"></i> 收藏</a>&nbsp;&nbsp;<a id="food_id_<?php echo $food['food_id'];?>_<?php echo intval($food['is_package']);?>" class="btn btn-small <?php if($food['show']):?> yu-ding <?php else:?> yu-ding-disabled disabled <?php endif;?>" food_id="<?php echo $food['food_id'];?>" food_price="<?php echo $food['food_price'];?>" is_hot="<?php echo $food['is_hot'];?>"  is_package="<?php echo (int)$food['is_package'];?>"><i class="icon-check"></i> 预订</a></p>
	</div>
</li>
	<?php $i++;?>
	 <?php endif;?>  
<?php endforeach;?>
	</ul>