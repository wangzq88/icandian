 
 <div class="hd bgl_c"><h3 class="clearfix pl10 pr10"><a href="javascript:void(0);" onclick="collectionShop(this,<?php echo $shop['shop_id'];?>);"
class="fr btn btn-small mt5"><i class="icon-heart"></i> 收藏</a><ul class="breadcrumb fl" style="padding:0;background-color:transparent;margin:0;"><li><a href="/"><span class="icon-home"></span></a> <span class="divider">/</span></li><?php if(!$title):?><li class="active"><?php echo $shop['shop_name'];?></li><?php else:?><li class="divider"><a href="/shop_<?php echo $shop['shop_id'];?>.html"><?php echo $shop['shop_name'];?></a><span class="divider">/</span></li><?php endif;?><?php if($title):?><li class="active"><?php echo $title;?></li><?php endif;?></ul></h3></div>
                        <div class="bd1 bg_f pb15 clearfix">
                            <div class="w60p fl">
                                <dl class="tip_ct p15">
                                    <dt class="tip_hd fl w40p <?php if($shop['flag'] == 0):?> close_state fb  <?php endif;?>">
                                        <img src="<?php echo $shop['shop_logo'] ? $shop['shop_logo']:'/images/no_pic.jpg'; ?>" class="big_pic" />
<!--                                        <p class="vm"><img src="/images/start_l.png" /><img src="/images/start_l.png" /><img src="/images/start_l.png" /><img src="/images/start_l.png" /><img src="/images/start_g.png" /><span class="r3 pl5"><strong class="Georgia f30">4</strong> 分</span>
                                        </p>-->
                                    </dt>                            
                                    <dd class="tip_bd fr w60p">
                                        <h4 class="f16 lh180 fb"><?php echo $shop['shop_name'];?></h4>
                                        <p><strong>菜系：</strong><span><?php echo $shop['shop_cuisine'];?></span></p>
<!--                                        <p><strong>配送范围：</strong>到金网电脑商城 30元。</p>
                                        <p><strong>起送价：</strong> 30元。</p>-->
                                        <p><strong>营业时间：</strong><?php echo $shop['shop_opening_hours'];?></p>
                                        <p><strong>最佳订餐：</strong><?php echo $shop['ordering_time'];?></p>
                                        <p><strong>地址信息：</strong><?php echo strip_tags($shop['shop_address']);?></p>
                                     	<p><strong>餐厅简介：</strong><?php echo strip_tags($shop['shop_description']);?></p>
                                    </dd>
                                </dl>
                            </div>
                            <div class="w40p fr">
                                <div class="store_des_info clearfix">
                                    
                                       <p class="lh180"><strong>公告：</strong><?php echo strip_tags($shop['shop_announcement']);?></p>           
                                       <p class="lh180"><strong>温馨提示：</strong><?php echo strip_tags($shop['shop_tips']);?></p>
                                       <?php if($menu):?>
                                       <p class="lh180"><strong>留言板：</strong><abbr title="对商家的反馈可以在这里提出"><a href="/shop_comment_<?php echo $shop['shop_id'];?>.html">进入</a></abbr></p>
                                       <?php else:?>
                                       <p class="lh180"><strong>餐店主页：</strong><abbr title="返回餐店的主页查看菜单"><a href="/shop_<?php echo $shop['shop_id'];?>.html">返回</a></abbr></p>                                       
                                       <?php endif;?>

                                </div>                        	
                            </div>
                        </div>