<div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>            
                <li>
                  <a href="/index.php?r=user">个人中心</a> <span class="divider">/</span>
                </li>
                <li class="active">我收藏的美食</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main fr w79p">
              	<div class="hd bgl_c">
                	<h2 class="pl15">我收藏的美食<span class="fn f12 pl5">(共<strong class="plr5 o3"><?php echo $total;?></strong>个)</span></h2>
                </div>
                <div class="bd p15 bd1">
					<table class="table table-bordered table_a mt10">
                    	<thead>
                        	<tr>
                              <th class="w20p">美食</th>
                              <th class="w40p">所属餐厅</th>
                              <th class="w10p">单价</th>
                              <th class="w10p">订购</th>
  <!--                            <th class="w10p">人气</th>-->
                              <th class="w10p">删除</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($collection_food_list && is_array($collection_food_list)):?>
                        	<?php foreach($collection_food_list as $collection):?>
                        	<tr>
                            	<td><?php echo $collection['food_name'];?></td>
                                <td><a href="<?php echo $this->createUrl('shop/index',array('id'=>$collection['shop_id']));?>"><?php echo $collection['shop_name'];?></a></td>
                                <td>￥<span class="r6"><?php echo $collection['food_price'];?></span></td>
								<td><span class="btn btn-small" onclick="book_food(this,<?php echo $collection['food_id'];?>,<?php echo $collection['is_package'];?>)"><i class="icon-shopping-cart"></i></span></td>                                
                            	<!--<td><span class="btn btn-small"><i class="icon-fire"></i> 22</span></td>-->
                                <td><span class="btn btn-small" onclick="delete_collection_food(this,<?php echo $collection['id'];?>)"><i class="icon-trash"></i></span></td>
                            </tr> 
                            <?php endforeach;?>
                        <?php else:?>
                        	<tr>
                            	<td colspan="5">您还没有收藏美食</td>
                            </tr> 
                       <?php endif;?>
                        </tbody>
                    </table>
                    <?php $this->widget('BootstrapPaging',array('page'=>$page,'total_page'=>$total_page)); ?>
                    <!--/分页-->			                    
                 </div>    
              </div>   
               <!--/中心右边栏-->   
               <!--/中心左边栏-->      
               <?php $this->widget('UserMenu'); ?>   
            </div>
			
        </div>