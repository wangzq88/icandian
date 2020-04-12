<div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li>
                  <a href="/index.php?r=user">个人中心</a> <span class="divider">/</span>
                </li>
                <li>
                  <a href="/index.php?r=message">信息中心</a> <span class="divider">/</span>
                </li>                
                <li class="active">详细信息</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main fr w79p">
              	<div class="hd bgl_c">
                	<h2 class="pl15">信息中心</h2>
                </div>
                <div class="bd p15 bd1">
					<div class="detail_new_content">
                    	<div class="detail_new_hd bd6_b tc">
                            <!--<h2 class="f24">日本拟要求对中国海监飞机实施"警告射击"</h2>-->
                            <p class="tc lh30"><span class="pr15">发布人：<?php echo $model['send_name'];?></span><span>发布时间：<?php echo date('Y-m-d H:i:s',$model['timestamp']);?></span></p>
                        </div>
                        <div class="detail_new_bd ptb15">
							<p class="t2"><?php echo  strip_tags($model['message']);?></p> 
							                                                    
                        </div>
                        <div class="tc"><a href="/index.php?r=message"><span class="btn btn-small">返回</span></a></div>
                    </div>			                    
                </div>
              </div>
              <!--/中心右边栏-->              
              <?php $this->widget('UserMenu'); ?>
              <!--/中心左边栏-->
            </div>
			
        </div>