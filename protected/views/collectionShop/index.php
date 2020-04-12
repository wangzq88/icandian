<div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>            
                <li>
                  <a href="/index.php?r=user">个人中心</a> <span class="divider">/</span>
                </li>
                <li class="active">我收藏的餐店</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main fr w79p">
              	<div class="hd bgl_c">
                	<h2 class="pl15">我收藏的餐店<span class="fn f12 pl5">(共<strong class="plr5 o3"><?php echo $total;?></strong>个)</span></h2>
                </div>
                <div class="bd p15 bd1">
                    <?php if($collection_shop_list && is_array($collection_shop_list)):?>
					<ol class="fav_store_lst">
                    	<?php foreach($collection_shop_list as $key => $shop):?>
                    	<li <?php if($key/2 == 0):?>class="well well-small"<?php endif;?>>
                        	<dl class="clearfix">
                            	<dt class="fl w15p">
                                	<img src="<?php echo $shop['shop_logo'] ? $shop['shop_logo']:SHOP_DEFAULT_LOGO;?>" alt="Logo" class="fav_store_pic" width="78" height="78"/>
                                </dt>
                                <dd class="fr w85p">
                                	<h4 class="w100p"><a class="close fr" onclick="delete_collection_shop(this,<?php echo $shop['id'];?>)">&times;</a><a href="/index.php?r=shop&id=<?php echo $shop['shop_id'];?>"><?php echo $shop['shop_name'];?></a></h4>
                                    <?php if(!$shop['open']):?><span class="label mr5">休息中</span><?php else:?><span class="label label-success">营业中</span><?php if($shop['coupon']):?><span class="label label-important mr5">卷</span><?php endif;?><?php endif;?>
                                    <?php if(!$shop['open']):?>
                                    	<p class="lh24">营业时间：<span><?php echo $shop['now_opening_hours'];?></span></p>
                                    	<p class="lh24">暂时不接受新订餐</p>
                                    <?php else: ?>
                                    	<p class="lh24">最佳订餐时间：<span><?php echo $shop['now_ordering_time'];?></span></p>
                                    	<p class="lh24">欢迎订餐</p>
                                    <?php endif;?>
                                </dd>
                            </dl>
                        </li>
                        <?php endforeach;?>
                    </ol>
                    <?php else:?>    
                    	<p class="tc">您还没有收藏餐店</p>
                    <?php endif;?>                        
                    <!--/分页-->	 
                    <?php $this->widget('BootstrapPaging',array('page'=>$page,'total_page' => $total_page)); ?>
                    <!--/我收藏的餐厅-->	    			                    
                </div>
                    
              </div>
              <!--/中心右边栏-->              
              <?php $this->widget('UserMenu'); ?>
              <!--/中心左边栏-->
            </div>
			
        </div>