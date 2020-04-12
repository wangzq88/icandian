<div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li>
                  <a href="/index.php?r=user">个人中心</a> <span class="divider">/</span>
                </li>
                <li class="active">我的地址</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main fr w79p">
              	<div class="hd bgl_c">
                	<h2 class="pl15">我的地址</h2>
                </div>
                <div class="bd p15 bd1">
                	<h3 class="lh30 r6">已经保存的送餐地址</h3>
					<table class="table table-bordered table_a mt10" id="address_list">
                    	<thead>
                        	<tr>
                              <th class="w50p">地址</th>
<!--                              <th class="w15p">联系电话</th>
                              <th class="w15p">备选电话</th>-->
                              <th class="w20p">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($address_list && is_array($address_list)):?>
                        	<?php foreach($address_list as $address): ?>
                        	<tr>
                            	<td><?php echo $address['address'];?></td>
                                <td style="overflow:visible;">
                                    <div class="btn-group">
     <!--                                 <a class="btn" title="默认地址"><i class="icon-home"></i></a>-->
                                      <!--<a class="btn" title="编辑"><i class="icon-pencil"></i></a>-->
                                      <a class="btn" onclick="deleteAddress(this,<?php echo$address['id'];?>);"><i class="icon-trash"></i></a>
                                    </div>
        						</td>
                            </tr>
                            <?php endforeach;?>
                        <?php else:?>
                        	<tr>
                            	<td colspan="2" id="friendly-table-tip">你还没填写送餐地址，只有先填写送餐地址，才能够结算喔！</td>
                            </tr>
                        <?php endif;?>                                                            
                        </tbody>
                    </table>
                	<h3 class="lh30 r6">新增/编辑送餐地址<span class="g9 f12">(带<i class="r6">*</i>为必须项)</span></h3>
                        <form method="post" action="/index.php?r=userAddress/create" class="q_login_form active">
                <?php if(isset($_GET['direct']) && $_GET['direct']): ?>
                    <input type="hidden" name="direct" value="<?php echo $_GET['direct'];?>" />
                <?php endif;?>                        
                            <label><span class="label_des"><b class="r3">∗</b>省份:</span><select name="shop_province" id="shop_province" class="span2"><?php foreach($province_list as $province) :?><option value="<?php echo $province['province_id'];?>"><?php echo $province['province_name']?></option><?php endforeach;?></select></label>
                            <label><span class="label_des"><b class="r3">∗</b>城市:</span><select name="shop_city" id="shop_city" class="span2"></select></label>
                            <label><span class="label_des"><b class="r3">∗</b>送餐地址:</span><input type="text" class="s_input" name="address"/><b class="pl10 fn">(请尽量详细一点)</b></label>
                            <div class="pl85"><span class="add_tbn_red"><input type="button" class="btn_red" value="提交" onclick="addAddressAction(this);"  data-loading-text="请稍候..." autocomplete="off"></span></div>            		</form>					                    
                </div>
              </div>
              <!--/中心右边栏-->              
               <?php $this->widget('UserMenu'); ?>
              <!--/中心左边栏-->
            </div>
			
        </div>
        <script type="text/javascript">

 var cities = new Array(<?php echo count($cities);?>);
 <?php 
 	if($cities && is_array($cities)) {
		foreach($cities as $key => $city) {
			echo 'cities['.$key.'] = {"city_id":"'.$city['city_id'].'","city_name":"'.$city['city_name'].'","province_id":"'.$city['province_id'].'"};';
			echo "\n";
		}
	}
 ?>
$('#shop_province').click(function(event) {
	 $('#shop_city').empty();
	 var provinceID = $(this).val();
	 var i = 0;
	 for(i in cities) {
		 if(cities[i].province_id == provinceID) {
			 $('#shop_city').append('<option value="'+cities[i].city_id+'">'+cities[i].city_name+'</option>');
		 }
	 }
	  $('#shop_city').show();
}).trigger('click');	 		
		</script>