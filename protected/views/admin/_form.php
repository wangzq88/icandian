<!--编辑分类窗口-->
<div id="edit-dlg" class="easyui-dialog" style="width:700px;height:500px;padding:10px 20px"
        closed="true" buttons="#edit-dlg-buttons">
<form id="edit-ff" method="post" action="/index.php?r=admin/update"> 
<input type="hidden" name="shop_id" />
            <table>  
            	<tr>
                	<td>商店：</td>
                    <td colspan="3"><input type="text" name="shop_name" readonly="readonly" /></td>
                </tr>
                <tr>  
                    <td>详细地址：</td>  
                    <td colspan="3"><input type="text" name="shop_address" readonly="readonly"/></td>  
                </tr>  
                <tr>  
                    <td>省份：</td>  
                    <td><select name="shop_province" id="shop_province"><?php foreach($province_list as $province) :?><option value="<?php echo $province['province_id'];?>"><?php echo $province['province_name']?></option><?php endforeach;?></select></td>  
                    <td>城市：</td>
                    <td><select name="shop_city" id="shop_city"></select></td>
                </tr>  
                <tr>  
                    <td>区域：</td>  
                    <td><select name="shop_region" id="shop_region"></select></td>  
                    <td>地段：</td>
                    <td><select name="shop_area" id="shop_area"><option value="0">不限</option></select></td>
                </tr>
                <tr>  
                    <td>具体路段：</td>  
                    <td colspan="3"><select name="shop_section" id="shop_section"><option value="0">不限</option></select></td>  
                </tr>
                <tr>  
                    <td>优惠：</td>  
                    <td><input type="radio" name="coupon" value="1" />有
                    <input type="radio" name="coupon" value="0" />没有
                    </td>  
                    <td>审核：</td>  
                    <td><input type="radio" name="status" value="1" />通过
                    <input type="radio" name="status" value="0" />不通过
                    </td>                      
                </tr> 
                <tr>  
                    <td>排序：</td>  
                    <td colspan="3"><input class="easyui-numberbox" type="text" /> </td>  
                </tr>                                                                    
               </table>
               </form>        
</div>        
<div id="edit-dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitEditForm();">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#edit-dlg').dialog('close')">取消</a>
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
  var regions = new Array(<?php echo count($regions);?>);
 <?php 
 if($regions && is_array($regions)) {
 	foreach($regions as $key => $region) {
		echo 'regions['.$key.'] = {"region_id":"'.$region['region_id'].'","region_name":"'.$region['region_name'].'","city_id":"'.$region['city_id'].'"};';
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
});
$("body").delegate("#shop_city", "click", function() {
	 $('#shop_region').empty();
	 var cityID = $(this).val();
	 var i = 0;
	 for(i in regions) {
		 if(regions[i].city_id == cityID) {
			 $('#shop_region').append('<option value="'+regions[i].region_id+'">'+regions[i].region_name+'</option>');
		 }
	 }
	  $('#shop_region').show();	
});
//------结束 	
 
 function editShangjia() {
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#edit-dlg').dialog('open').dialog('setTitle','编辑审核');
		$('#edit-ff').form('load',row);
	}	 
 }
		
function submitEditForm() {
	$.post($("#edit-ff").attr("action"),$("#edit-ff").serialize(),function(data) {
		if(data.success == 0)
			$.messager.alert('提示',data.info,'warning');
		else {
			$('#edit-dlg').dialog('close');
			$('#dg').datagrid('reload'); 
		}
	});			
}

function delShangjia(){
		var row = $('#dg').datagrid('getSelected');
		if (row){
			$.messager.confirm('确认','一旦删除将不能恢复，你确定要删除选中的记录吗？',function(r){
				if (r){
					$.post('/index.php?r=admin/delete',{categories_id:row.categories_id},function(result){
						$('#dg').datagrid('reload'); 	// reload the user data
					},'json');
				}
			});
		}
}		
		
function searchCategories() {
	//console.log();
	var result_list = $('#search-form').serializeArray();
	$("#dg").datagrid('load',{
		categories_name: result_list[0].value
	});
}		
		function searchKeyEventHandler(event) {
			var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
			if (keyCode == 13) {
				if(event.type == 'keyup') {
					searchCategories();	
				}
				return false
			}	
			return true;	
		}			
    </script>        