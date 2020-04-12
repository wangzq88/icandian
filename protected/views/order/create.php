<div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li class="active">提交订单</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main w100p">
                  <div class="hd bgl_c">
                      <p class="tc f12"><span class="plr5">1.选择美食</span>→<span class="plr5">2.填写核对订单信息 </span>→<span class="r6 plr5">3.成功提交订单</span></p>
                  </div>
                  <div class="bd">
                      <ol class="sucessful_lst">
                      	<li>
                        	<dl class="w100p suc_detail">
                            	<dt class="fl w20p"><img src="/images/shop_suc.png" /></dt>
                                <dd class="fr w80p">
                                	<p class="fb lh30">您已成功订餐，你的订单信息已经成功发送给商家，请耐心等待。
 餐到后须支付<b class="f24 r6 plr10"><?php echo $model->price;?></b>元</p>
                                	<p class="lh30"><span><strong>订单编号：</strong><?php echo $model->order_number;?></span><a href="/index.php?r=order/view&order_id=<?php echo $model->order_id;?>" class="ml15">查看已买的美食</a></p>
                                   <!-- <p class="lh30"><span><strong>订餐电话：</strong>13521228746</span></p>
                                	<p><a href="/index.php?r=order/Orderdetail&order_id=<?php echo $model->order_id;?>" class="btn btn-small mr15">查看已买的美食</a><a href="#" class="btn btn-small">给对方评价</a></p>-->
                                </dd>
                            </dl>
                        	<dl class="w100p suc_detail">
                            	<dt class="fl w20p"><img src="/images/b_smile.png" /></dt>
                                <dd class="fr w80p">
                                	<p class="fb lh30">恭喜你！这笔订单你交易成功可以获得<b class="f24 r6 plr10"><?php echo $model->integration;?></b>积分</p>
<!--                                	<p><a href="/index.php?r=order/Orderdetail&order_id=<?php echo $model->order_id;?>" class="btn btn-small mr15">查看已买的美食</a></p>-->
                                </dd>
                            </dl>                            
                        </li>
                      </ol>
                  </div>                        
              </div>
              <!--/中心右边栏-->              

            </div>
			
        </div>
        <script type="text/javascript">
jQuery(function($) {	
	if(systemDB) {	
		dropTables(systemDB);
		createTables(systemDB);
	} else if(typeof(Storage)!=="undefined") {
		localStorage.shopping = '';
	} else {
		setCookie("shopping",'');
	}
});
		</script>