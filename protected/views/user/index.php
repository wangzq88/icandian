<div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li class="active">个人中心</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main fr w79p">
              	<div class="hd bgl_c">
                	<h2 class="pl15">个人中心</h2>
                </div>
                <div class="bd p15 bd1">
                <?php if($_GET['info']):?>
                	<div class="alert <?php echo $_GET['style'];?>">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <strong><?php echo $_GET['info'];?></strong>
                    </div>
                <?php endif;?>
                	<table class="table_b" width="100%">
                    	<thead>
                        	<tr>
                            	<th colspan="2">
                                	<dl class="clearfix">
                                    	<dt class="fl w10p tl"><img src="<?php echo isset($user->avatar) ? $user->avatar:'/images/avatar_gray.png'; ?>" /></dt>
                                        <dd class="fl w70p">
                                        	<h4 class="tl lh24"><?php echo $user->username;?></h4>
                                            <div class="clearfix">
                                                <b class="pl10 o3 fr"><?php echo $security_text;?></b>
                                                <strong class="fl">安全等级：</strong>
                                                <div class="progress progress-warning" style="margin:0; height:15px">
                                                  <div class="bar" style="width: <?php echo $security;?>%;"></div>
                                                </div>
                                            </div>
                                            <div></div>
                                        </dd>
                                    </dl>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                       	  <tr>
                            	<td colspan="2" class="bd5_tb">
                                  <table width="100%" class="table_b-child">
                                    <tr>
                                      <td>积分：<span class="o3 plr5"><?php echo $user->integration;?></span>点</td>
                                      <td>收藏：<span class="o3 plr5"><?php echo $user->collection_shop;?></span>家餐厅<span class="o3 plr5"><?php echo $user->collection_food;?></span>份美食</td>
                                      
                                    </tr>
                                    <tr>
										<td>注册邮箱：<span class="o3 plr5"><?php echo $user->email ? $user->email:'未验证';?></span><a href="javascript:void(0);" title="修改" onclick="triggerEmailRequestAction(this);"><i class="icon-edit"></i></a></td>
                                        <td>手机号码：<span class="o3 plr5"><?php echo isset($user->mobile) && $user->mobile ? $user->mobile:'未验证';?></span><a href="/index.php?r=user/updateMobile" title="修改"><i class="icon-edit"></i></a></td>
                                    </tr>
									<tr>
                                    	<td>上次登录IP：<span class="o3 plr5"><?php echo $user->ip;?></span></td>
                                        <td>上次登录时间：<span class="o3 plr5"><?php echo date('Y-m-d H:i:s',$user->last_visit);?></span></td>
                                    </tr>
                                  </table>
                              </td>
                          </tr>
                        </tbody>
                    </table>
                </div>
              </div>
              <!--/中心右边栏-->              
               <?php $this->widget('UserMenu'); ?>
              <!--/中心左边栏-->
            </div>
			
        </div>