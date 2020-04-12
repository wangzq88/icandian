<div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li>
                  <a href="/index.php?r=user">个人中心</a> <span class="divider">/</span>
                </li>
                <li class="active">信息中心</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main fr w79p">
              	<div class="hd bgl_c">
                	<h2 class="pl15">信息中心</h2>
                </div>
                <div class="bd p15 bd1">
					<table class="table table-bordered table_a mt10">
                    	<thead>
                        	<tr>
                              <th class="w20p tl">发布人</th>
                              <th class="w60p tl">消息标题</th>
                              <th class="w20p tl">发布时间</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($message_list && is_array($message_list)):?>
                        	<?php foreach($message_list as $message):?>
                        	<tr>
                            	<td class="tl"><?php echo $message['send_name'];?></td>
                                <td class="tl"><a href="/index.php?r=message/view&id=<?php echo $message['id'];?>"><?php echo $message['message'];?></a></td>
                                <td class="tl"><?php echo date('Y-m-d H:i:s',$message['timestamp']);?></td>
                            </tr>
                            <?php endforeach;?>
                        <?php else:?>
                        	<tr>
                            	<td colspan="3">没有新的信息</td>
                            </tr>
                        <?php endif;?>
                        </tbody>
                    </table>
                    <?php $this->widget('BootstrapPaging',array('page'=>$page,'total_page' => $total_page)); ?>
                    <!--/分页-->
              </div>
              <!--/中心右边栏--> 
              </div>             
                            <?php $this->widget('UserMenu'); ?>
              <!--/中心左边栏-->	
            </div>		
        </div>