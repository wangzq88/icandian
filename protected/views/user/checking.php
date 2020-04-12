<style type="text/css">
.table_address {
	margin-bottom: 20px;
}
</style>
<div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li>
                  <a href="/index.php?r=user">个人中心</a> <span class="divider">/</span>
                </li>
                <li class="active">购物车</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main fr w79p">
              	<div class="hd bgl_c">
                	<h2 class="plr15 fn clearfix"><span class="fr f12"><span class="plr5">1.选择美食</span>→<span class="r6 plr5">2.填写核对订单信息 </span>→<span class="plr5">3.成功提交订单</span>
</span><strong class="fl">我的购物车</strong></h2>
                </div>
                <div class="bd p15 bd1">
                <?php if($_GET['info']):?>
                	<div class="alert <?php echo $_GET['style'];?>">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <strong><?php echo $_GET['info'];?></strong>
                    </div>
                <?php unset($_GET['info']);?>
                <?php endif;?>                
                	<h3 class="lh30 r6">我的美食</h3>
					<table class="table table-bordered table_a mt10" id="my-shopping-cart">
                    	<thead>
                        	<tr>
                              <th class="w30p">餐厅名称</th>
                              <th class="w30p">菜品</th>
                              <th class="w15p">数量</th>
                              <th class="w15p">单价</th>
                              <th class="w10p">操作</th>
                            </tr>
                        </thead> 
                        <tbody><tr><td colspan="5" id="friendly-table-tip">你的购物车还没有任何美食喔，赶快点一份吧！</td></tr></tbody>                     
                    </table>
                	<h3 class="lh30 fn"><strong class="r6 pr5">确认你的送餐地址</strong><span class="g9 f12 ml15"><a href="/index.php?r=userAddress&direct=<?php echo urlencode($_SERVER['REQUEST_URI']);?>" title="修改"><i class="icon-edit"></i></a></span></h3>
                    <div class="table_address">
                    <table class="table table_d">
                    	<tbody>
                        <?php $has_address = false;?>
                        <?php $first_address = '';?>
                        <?php if($address_list && is_array($address_list)):?>
                        	<?php $has_address = true;?>
                        	<?php foreach($address_list as $key => $address) :?>
                        	<tr>
                            	<td <?php if($key == 0) :?>class="bg_fe"<?php $first_address = $address['address'];endif;?>><input type="radio" name="chose_address" value="<?php echo $address['id'];?>" <?php if($key == 0) :?>checked <?php endif;?>/><span class="pl5"><?php echo $address['address'];?></span></td>
                           </tr>
                           <?php endforeach;?>
                        <?php else:?>
                        	<tr>
                            	<td>你还没填写送餐地址，只有填写之后才能提交订单。</td>
                            </tr>
                        <?php endif;?>
                          
                        </tbody>
                    </table>
                    </div>
			
            		<h3 class="lh30 fn"><strong class="r6 pr5">确认你的手机号码</strong><span class="g9 f12 ml15"><a href="/index.php?r=user/updateMobile&direct=<?php echo urlencode($_SERVER['REQUEST_URI']);?>" title="修改"><i class="icon-edit"></i></a></span></h3>
                    <div class="table_address">
                    <table class="table table_d">
                    	<tbody>
                  		<?php if(isset(Yii::app()->user->mobile) && Yii::app()->user->mobile):?>
                        	<tr>
                            	<td><?php echo Yii::app()->user->mobile;?></td>
                           </tr>
                        <?php else:?>
                        	<tr>
                            	<td>你还没填写<abbr title="商家会通过这个号码联系你，确定订餐的相关信息">手机号码</abbr>，只有填写之后才能提交订单。</td>
                            </tr>
                        <?php endif;?>
                          
                        </tbody>
                    </table>
                    </div>
                  <form action="/index.php?r=order/create" method="post" id="submit-order-form">
                  	<input type="hidden" name="address_id" value="" id="address_id"/>
                  	<input type="hidden" name="order" id="order"/>
                  	<input type="hidden" name="address" value="<?php echo $first_address;?>" id="address"/>
                  <div class="tc"><span class="add_tbn_red"><input type="button" class="btn_red" value="提交" onclick="submitOrderAction(identity_flag)" data-loading-text="请稍候..." autocomplete="off"></span></div>	                    
                  </form>
                </div>
              </div>
              <!--/中心右边栏-->              
              <?php $this->widget('UserMenu'); ?>
              <!--/中心左边栏-->
            </div>
			
        </div>
<script type="text/javascript">
jQuery(function($) {
	 generateOrdersAction(identity_flag);
});
</script>