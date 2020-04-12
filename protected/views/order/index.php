        <div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li>
                  <a href="/index.php?r=user">个人中心</a> <span class="divider">/</span>
                </li>
                <li class="active">历史订单</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main fr w79p">
              	<div class="hd bgl_c">
                	<h2 class="pl15">历史订单</h2>
                </div>
                <div class="bd p15 bd1">
                	<table class="table table-bordered table_a">
                    	<thead>
                        	<tr>
                              <th class="w25p">订单号</th>
                              <th class="w25p">下单时间</th>
                              <th class="w15p">订单金额</th>
                              <th class="w10p">积分</th>
                              <th class="w15p">订单详情</th>
                              <th class="w10p">状态</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($order_list && is_array($order_list)):?>
                        <?php foreach($order_list as $order): ?>
                        	<tr>
                            	<td class="o3"><?php echo $order['order_number'];?></td>
                                <td><?php echo date('Y-m-d H:i',$order['timestamp']);?></td>
                                <td>￥<?php echo $order['price'];?></td>
                                <td><?php echo $order['integration'];?></td>
                                <td class="b0"><div class="ell" onclick="location.href='/index.php?r=order/view&order_id=<?php echo $order['order_id'];?>';" style="cursor:pointer">查看</div></td>
                                <td><?php if($order['flag']):?><i class="ok f20">&radic;</i><?php else:?><i class="icon-ban-circle"></i><?php endif;?></td>
                            </tr>
                        <?php endforeach;?>
                        <?php endif;?>                                                                                                             
                        </tbody>
                    </table>
                   <?php $this->widget('BootstrapPaging',array('page'=>$page,'total_page' => $total_page)); ?>

                </div>      
          
              </div>
              <!--/中心右边栏-->              
               <?php $this->widget('UserMenu'); ?>
              <!--/中心左边栏-->
            </div>
			
        </div>	
        <!--/个人中心-->	