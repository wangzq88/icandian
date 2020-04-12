<style type="text/css">
.label{text-align:right;}
#dlg table td,#edit-dlg table td{ padding:4px 0;}
#dlg tr td:first-child,#edit-dlg tr td:first-child{text-align:right; padding:0 10px 0 20px;}
#dlg tr td:first-child a,#edit-dlg  tr td:first-child a{cursor: help;border-bottom: 1px dotted #999999;text-decoration:none; color:inherit;}
</style>
<table id="dg" class="easyui-datagrid" style="height:500px"  url="/index.php?r=admin/already" data-options="toolbar:'#toolbar',pagination:true,rownumbers:true,fitColumns:true,singleSelect:true,">  
	<thead>  
		<tr>  
        	<th data-options="field:'shop_id',hidden:true">ID</th>
			<th data-options="field:'shop_name',width:80,align:'center'">商店名称</th>  
            <th data-options="field:'uid',hidden:true">商家UID</th>  
            <th data-options="field:'user_name',width:50,align:'center'">商家</th>
			<th data-options="field:'shop_description',width:150,align:'center'">商家介绍</th>
            <th data-options="field:'shop_tips',width:100,align:'center'">温馨提示</th>
            <th data-options="field:'shop_announcement',width:150,align:'center'">公告</th>
            <th data-options="field:'shop_address',width:150,align:'center'">详细地址</th>
            <th data-options="field:'coupon',hidden:true">优惠标识</th>
            <th data-options="field:'coupon_text',width:50,align:'center'">优惠</th>
            <th data-options="field:'flag',hidden:true">状态标识</th>
            <th data-options="field:'flag_text',width:50,align:'center'">状态</th>
            <th data-options="field:'status',hidden:true">审核标识</th>
            <th data-options="field:'status_text',width:50,align:'center'">审核</th>
            <th data-options="field:'ordering',width:50,align:'center'">排序</th>  
		</tr>  
	</thead>  
</table>
<div id="toolbar" style="padding:5px 10px;">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="viewShangjia()">查看</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editShangjia()">编辑</a>  
<form style="display:inline-block;float:right;" action="/index.php?r=admin/already" id="search-form">
商店名称：<input type="text" name="shop_name" style="width:120px" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);"/>&nbsp;
        优惠：<select name="coupon" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        <option value="-1">----</option> 
        <option value="0">无</option> 
        <option value="1">有</option> 
        </select>&nbsp;
        审核：<select name="status" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        <option value="-1">----</option> 
        <option value="0">不通过</option> 
        <option value="1">通过</option> 
        </select>&nbsp;
<a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="searchShangjia()">搜索</a> 
</form>        
</div>
<!--查看商家-->
<div id="dlg" class="easyui-dialog" style="width:600px;height:500px;padding:10px 10px 0 10px;" data-options="resizable:false,modal:true,closed:true">
<form id="ff"> 
            <table>  
                <tr>  
                	<td class="label">商店：</td>
                    <td><input type="text" name="shop_name" readonly="readonly" /></td> 
                </tr> 
                <tr>  
                    <td class="label">商家：</td>  
                    <td><input type="text" name="user_name" readonly="readonly"/></td>  
                </tr>                 
                <tr>  
                    <td class="label">商家介绍：</td>  
                    <td><textarea name="shop_description" cols="50" rows="5" readonly="readonly"></textarea></td>  
                </tr> 
                <tr>  
                    <td class="label">温馨提示：</td>  
                    <td>
                    	<textarea name="shop_tips" cols="50" rows="3" readonly="readonly"></textarea>
                    </td>  
                </tr>  
                <tr id="view_shop_announcement">  
                    <td class="label">公告：</td>  
                    <td>
                    	<textarea name="shop_announcement" cols="50" rows="5" readonly="readonly"></textarea>
                     </td>  
                </tr>                                                                
                                                                              
               </table>
               </form>        
</div>                
<!--编辑审核窗口-->
<div id="edit-dlg" class="easyui-dialog" style="width:600px;height:400px;padding:10px 20px 0 50px;"
     buttons="#edit-dlg-buttons" data-options="resizable:false,modal:true,closed:true">
<form id="edit-ff" method="post" action="/index.php?r=admin/shopUpdate"> 
<input type="hidden" name="shop_id" />
            <table>  
            	<tr>
                	<td class="label">商店：</td>
                    <td><input type="text" name="shop_name" /></td>
                </tr>
                <tr>  
                    <td class="label">详细地址：</td>  
                    <td><input type="text" name="shop_address" size="40"/></td>  
                </tr>  
                <tr>  
                    <td class="label">省份：</td>  
                    <td><select name="shop_province" id="shop_province"><?php foreach($province_list as $province) :?><option value="<?php echo $province['province_id'];?>"><?php echo $province['province_name']?></option><?php endforeach;?></select></td>  
                  </tr>
                  <tr>
                    <td class="label">城市：</td>
                    <td><select name="shop_city" id="shop_city"></select></td>
                </tr>  
                <tr>  
                    <td class="label">区域：</td>  
                    <td><select name="shop_region" id="shop_region"></select></td>  
                </tr>
                <tr>
                    <td class="label">地段：</td>
                    <td><select name="shop_area" id="shop_area"><option value="0">不限</option></select></td>
                </tr>
                <tr>  
                    <td class="label">具体路段：</td>  
                    <td><select name="shop_section" id="shop_section"><option value="0">不限</option></select></td>  
                </tr>
                <tr>  
                    <td class="label">优惠：</td>  
                    <td><input type="radio" name="coupon" value="1" />有
                    <input type="radio" name="coupon" value="0"/>无
                    </td>  
                    </tr>
                    <tr>
                    <td class="label">审核：</td>  
                    <td><input type="radio" name="status" value="1" />通过
                    <input type="radio" name="status" value="0" />不通过
                    </td>                      
                </tr> 
                <tr>  
                    <td class="label">排序：</td>  
                    <td><input class="easyui-numberbox" type="text" name="ordering" id="ordering"/> </td>  
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
	  $('#shop_region').show().trigger('click');	
});
$("body").delegate("#shop_region", "click", function() {
	 var regionID = $(this).val();
	 $.get('/index.php?r=area/getAreaList','region_id='+regionID,function(areas) {
		 if(areas.length > 0) {
			 $('#shop_area').empty().append('<option value="0">不限</option>');
			 var i = 0;
			 for(i in areas) {
				 if(areas[i].region_id == regionID) {
					 $('#shop_area').append('<option value="'+areas[i].area_id+'">'+areas[i].area_name+'</option>');
				 }
			 }
		 }			 
	 });
});
$("body").delegate("#shop_area", "click", function() {
	 var areaID = $(this).val();
	 $.get('/index.php?r=sections/getSectionList','area_id='+areaID,function(sections) {
		 if(sections.length > 0) {
			 $('#shop_section').empty().append('<option value="0">不限</option>');
			 var i = 0;
			 for(i in sections) {
				 if(sections[i].area_id == areaID) {
					 $('#shop_section').append('<option value="'+sections[i].section_id+'">'+sections[i].section_name+'</option>');
				 }
			 }
		 }			 
	 });
});
//------结束 	
 
 function viewShangjia() {
	 var row = $('#dg').datagrid('getSelected');
	 var html = '';
	 $("#view_shop_announcement").nextAll().remove();
	 document.getElementById('ff').reset();
	 if (row){	
	 	if($.trim(row.shop_opening_hours) != '') {
		 	var shop_opening_hours = $.parseJSON(row.shop_opening_hours);
			if($.isPlainObject(shop_opening_hours)) {
				var arr;
				$.each(shop_opening_hours,function( key, value ) {
					arr = value.split(' ');
					switch(key) {
						case '7':
							html += '<tr><td></td><td>星期日&nbsp;&nbsp;&nbsp;&nbsp;<label>'+arr[0]+'</label>&nbsp;&nbsp;<label>'+arr[1]+'</label></td></tr>';
							break;
						case '1':
							html += '<tr><td class="label">营业时间：</td><td>星期一&nbsp;&nbsp;&nbsp;&nbsp;<label>'+arr[0]+'</label>&nbsp;&nbsp;<label>'+arr[1]+'</label></td></tr>';
							break;
						case '2':
							html += '<tr><td></td><td>星期二&nbsp;&nbsp;&nbsp;&nbsp;<label>'+arr[0]+'</label>&nbsp;&nbsp;<label>'+arr[1]+'</label></td></tr>';
							break;	
						case '3':
							html += '<tr><td></td><td>星期三&nbsp;&nbsp;&nbsp;&nbsp;<label>'+arr[0]+'</label>&nbsp;&nbsp;<label>'+arr[1]+'</label></td></tr>';
							break;	
						case '4':
							html += '<tr><td></td><td>星期四&nbsp;&nbsp;&nbsp;&nbsp;<label>'+arr[0]+'</label>&nbsp;&nbsp;<label>'+arr[1]+'</label></td></tr>';
							break;	
						case '5':
							html += '<tr><td></td><td>星期五&nbsp;&nbsp;&nbsp;&nbsp;<label>'+arr[0]+'</label>&nbsp;&nbsp;<label>'+arr[1]+'</label></td></tr>';
							break;	
						case '6':
							html += '<tr><td></td><td>星期六&nbsp;&nbsp;&nbsp;&nbsp;<label>'+arr[0]+'</label>&nbsp;&nbsp;<label>'+arr[1]+'</label></td></tr>';
							break;																																									
					}
				});
				$("#view_shop_announcement").after(html);
			}
		}
		if($.trim(row.ordering_time) != '') {
			var ordering_time = $.parseJSON(row.ordering_time);
			var html;
			if($.isPlainObject(ordering_time)) {
				$.each(ordering_time,function( key, value ) {
					switch(key) {
						case '1':
							html = value+'&nbsp;&nbsp;';
							break;
						case '2':
							html += value;
							break;
					}
				});
				html = '<tr><td>订餐时间：</td><td>'+html+'</td></tr>';
				$("#view_shop_announcement").closest('table').append(html);
			}				
		}
		$('#ff').form('load',row);
		$('#dlg').dialog('open').dialog('setTitle','查看详情');		
	 }
 }
 
 function editShangjia() {
	var row = $('#dg').datagrid('getSelected');
	if (row){	
		$('#edit-dlg').dialog('open').dialog('setTitle','编辑审核');
		$('#edit-ff').form('load',row);
		$('#shop_province').trigger('click');
		$('#shop_city').val(row.shop_city).trigger('click');
		$('#shop_region').val(row.shop_region).trigger('click');	
		$('#shop_area').val(row.shop_area).trigger('click');	
				
		$('#edit-ff input:radio').filter('[name="coupon"]').filter('[value="'+row.coupon+'"]').attr("checked","checked");
		$('#edit-ff input:radio').filter('[name="status"]').filter('[value="'+row.status+'"]').attr("checked","checked");
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
		
function searchShangjia() {
	//console.log();
	var result_list = $('#search-form').serializeArray();
	$("#dg").datagrid('load',{
		shop_name: result_list[0].value,
		coupon: result_list[1].value,
		status:result_list[2].value
	});
}		
		function searchKeyEventHandler(event) {
			var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
			if (keyCode == 13) {
				if(event.type == 'keyup') {
					searchShangjia();	
				}
				return false
			}	
			return true;	
		}			
		

    </script>           