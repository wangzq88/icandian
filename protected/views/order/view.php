<div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li>
                  <a href="/index.php?r=user">个人中心</a> <span class="divider">/</span>
                </li>
                <li>
                  <a href="/index.php?r=order">历史订单</a> <span class="divider">/</span>
                </li>                
                <li class="active">订单详情</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main fr w79p">
              	<div class="hd bgl_c">
                	<h2 class="pl15">订单详情</h2>
                </div>
                <div class="bd p15 bd1">
                	<table class="table_b" width="100%">
<!--                    	<thead>
                        	<tr>
                            	<th class="w70p tl"><strong class="r6">兄弟同心面馆（石牌西路店）</strong></th>
                                <th class="w30p tr">
                                	<a class="btn btn-danger">再来一份</a>
                                    <a class="btn btn-success">取消订单</a>
                                </th>
                            </tr>
                        </thead>-->
                        <tbody>
                       	  <tr>
                            	<td colspan="2" class="bd5_tb">
                                  <table width="100%" class="table_b-child">
                                    <tr>
                                      <td><strong>订单号：</strong><?php echo $order['order_number'];?></td>
                                      <td><strong>下单时间：</strong><?php echo date('Y-m-d H:i:s',$order['timestamp']);?></td>
                                      
                                    </tr>
                                    <tr>
	                                  <td><strong>订单金额：</strong>￥<?php echo $order['price'];?></td>
                                      <td><strong>送餐地址：</strong><?php echo $order['address'];?></td>
                                    </tr>
<!--                                    <tr>
                                      <td><strong>餐厅电话：</strong>18022302856</td>
                                      <td><strong>餐厅备注：</strong>无</td>
                                    </tr>-->
                                  </table>
                              </td>
                          </tr>
                        </tbody>
                        <tfoot>
                        	<tr>
                            	<td colspan="2">
                                  <table width="100%" class="table_c">
                                    <tr>
                                    	<th class="w40p"><strong>餐店：</strong></th>
                                    	<th class="w40p"><strong>菜品：</strong></th>
                                        <th class="w10p"><strong>数量：</strong></th>
                                        <th class="w10p"><strong>单价：</strong></th>
                                        <th class="w10p"><strong>小计：</strong></th>
                                    </tr>
                           <?php $all_amount = $all_price = 0; ?>
                           <?php if($item_list && is_array($item_list)): ?>
                           <?php  foreach($item_list as $item): ?>
                           <?php 
						        $all_amount += $item['amount'];
								$all_price += $item['amount']*$item['food_price'];
							?>
                            <?php endforeach;?>
                          <?php  foreach($shop_list as $shop): ?>
                          	 <?php $first = true;?>
                             <?php  foreach($item_list as $item): ?>
                             	<?php if($shop['shop_id'] == $item['shop_id']) :?>
                                    <tr>
                                    <?php if($first):?>
                                      <td rowspan="<?php echo $shop['count'];?>"><?php echo $item['shop_name'];?></td>
                                    <?php 
										$first = false;
										endif;
									?>
                                      <td><?php echo $item['food_name'];?></td>
                                      <td><?php echo $item['amount'];?></td>
                                      <td>￥<?php echo $item['food_price'];?></td>
                                      <td>￥<?php echo number_format($item['food_price']*$item['amount'],1);?></td>
                                    </tr>
                                <?php endif;?>
                            <?php endforeach;?>
                          <?php endforeach;?>
                          <?php endif;?>
                                  </table>                                
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="1"></td>
                                <td colspan="1" class="tr"><span class="pr10">共<strong class="r6"> <?php echo $all_amount;?> </strong>份美食</span><span class="pr20">合计<b class="f20 fa r3"> <?php echo $all_price;?> </b>元</span></td>
                            </tr>
                        </tfoot>
                    </table>
                    <?php $this->widget('BootstrapPaging',array('page'=>$page,'total_page' => $total_page)); ?>
                </div>
              </div>
              <!--/中心右边栏-->              
              <?php $this->widget('UserMenu'); ?>
              <!--/中心左边栏-->
            </div>
			
        </div>