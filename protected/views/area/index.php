<table id="dg" class="easyui-datagrid" style="height:500px"  url="/index.php?r=area/index"  toolbar="#toolbar" pagination="true"  rownumbers="true" fitColumns="true" singleSelect="true">  
	<thead>  
		<tr>  
        	<th data-options="field:'area_id',hidden:true">ID</th>
            <th data-options="field:'area_name',width:80,align:'center'">地段</th> 
        	<th data-options="field:'region_id',hidden:true">区域ID</th>
			<th data-options="field:'region_name',width:80,align:'center'">区域</th>  
            <th data-options="field:'city_id',hidden:true">城市UID</th>  
            <th data-options="field:'city_name',width:100,align:'center'">城市</th>
            <th data-options="field:'province_id',hidden:true">省份ID</th>
            <th data-options="field:'province_name',width:100,align:'center'">省份</th>
            <th data-options="field:'ordering',width:50,align:'center'">排序</th>  
            <th data-options="field:'status',align:'center',hidden:true">显示</th> 
            <th data-options="field:'status_text',width:50,align:'center'">显示</th>  
		</tr>  
	</thead>  
</table>
<div id="toolbar" style="padding:10px;height:auto">
	<div style="margin-bottom:5px"> 
        <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newArea()">添加地段</a>  
        <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editArea()">编辑地段</a>  
     </div>
     <div>
<form style="display:inline-block;" id="search-form">
        省份：<select name="shop_province" id="shop_province" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        	<option value="0">不限</option>
		<?php foreach($province_list as $province) :?>
        	<option value="<?php echo $province['province_id'];?>"><?php echo $province['province_name']?></option>
		<?php endforeach;?>
        </select>&nbsp;
        城市：<select name="shop_city" id="shop_city" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        </select>&nbsp;   
        区域：<select name="shop_region" id="shop_region" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        </select>&nbsp;      
地段：<input type="text" name="area_name" style="width:120px" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);"/>&nbsp;  
显示：<select name="status" id="status" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        	<option value="-1">不限</option>		
            <option value="1">是</option>
        	<option value="0">否</option>
        </select>      
<a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-search" onclick="searchRegion()">搜索</a> 
</form>        
</div>
</div>

 <!--新增区域窗口-->
<div id="dlg" class="easyui-dialog" style="width:500px;height:350px;padding:10px 20px 0 50px"
 buttons="#dlg-buttons" data-options="resizable:false,modal:true,closed:true">
        <div style="text-align:center;display:none;font-weight:bold;" id="create_tip">区域已经成功添加！</div>
<form id="ff" method="post" action="/index.php?r=area/create"> 
            <table>  
                <tr>  
                    <td>省份：</td>  
                    <td>
                    	<select name="ff_province" id="ff_province">
							<?php foreach($province_list as $province) :?>
                                <option value="<?php echo $province['province_id'];?>"><?php echo $province['province_name']?></option>
                            <?php endforeach;?>
				        </select>
        			</td>  
                </tr> 
                <tr>  
                    <td>城市：</td>  
                    <td>
                    	<select name="city_id" id="ff_city">
        				</select>	
        			</td>  
                </tr>                 
                <tr>  
                    <td>区域：</td>  
                    <td><select name="region_id" id="ff_region">
                    </select>
                    </td>  
                </tr> 
                <tr>  
                    <td>地段：</td>  
                    <td><input class="easyui-validatebox" type="text" name="area_name" data-options="required:true" /></td>  
                </tr>                   
                <tr>  
                    <td>排序：</td>  
                    <td><input class="easyui-numberbox" type="text" name="ordering" data-options="required:true" value="0"/></td>  
                </tr>  
<tr>  
                    <td>显示：</td>  
                    <td><input type="radio" name="status" value="1" checked="checked"/>是&nbsp;&nbsp;<input type="radio" name="status" value="0" />否</td>  
                </tr>                                                                                          
               </table>
               </form>        
</div>        
<div id="dlg-buttons">
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitForm();">保存</a>
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlg').dialog('close')；">取消</a>
</div>  
<!--End 新增区域-->

<!--编辑区域窗口-->
<div id="edit-dlg" class="easyui-dialog" style="width:500px;height:350px;padding:10px 20px 0 50px"
  buttons="#edit-dlg-buttons" data-options="resizable:false,modal:true,closed:true">
        <div style="text-align:center;display:none;font-weight:bold;" id="edit_tip">区域已经成功更新！</div>
<form id="edit-ff" method="post" action="/index.php?r=area/update"> 
	<input type="hidden" name="area_id" />
            <table>  
                <tr>  
                    <td>省份：</td>  
                    <td>
                    	<select name="province_id" id="edit_ff_province">
							<?php foreach($province_list as $province) :?>
                                <option value="<?php echo $province['province_id'];?>"><?php echo $province['province_name']?></option>
                            <?php endforeach;?>
				        </select>
        			</td>  
                </tr> 
                <tr>  
                    <td>城市：</td>  
                    <td>
                    	<select name="city_id" id="edit_ff_city">
        				</select>	
        			</td>  
                </tr> 
                <tr>  
                    <td>区域：</td>  
                    <td>
                    	<select name="region_id" id="edit_ff_region">
        				</select>	
        			</td>  
                </tr>                                 
                <tr>  
                    <td>地段：</td>  
                    <td><input class="easyui-validatebox" type="text" name="area_name" data-options="required:true" /></td>  
                </tr>  
                <tr>  
                    <td>排序：</td>  
                    <td><input class="easyui-numberbox" type="text" name="ordering" data-options="required:true" value="0"/></td>  
                </tr>    
				<tr>  
                    <td>显示：</td>  
                    <td><input type="radio" name="status" value="1" />是&nbsp;&nbsp;<input type="radio" name="status" value="0" />否</td>  
                </tr>                                                                                              
               </table>
               </form>        
</div>        
<div id="edit-dlg-buttons">
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitEditForm();">保存</a>
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#edit-dlg').dialog('close')">取消</a>
</div>  
<!--End 编辑地段-->
<script type="text/javascript">
//搜索
 var cities = new Array(<?php echo count($cities);?>);
 <?php 
 	if($cities && is_array($cities)) {
		foreach($cities as $key => $city) {
			echo 'cities['.$key.'] = {"city_id":"'.$city['city_id'].'","city_name":"'.$city['city_name'].'","province_id":"'.$city['province_id'].'"};';
			echo "\n";
		}
	}
 ?>
  var regiones = new Array(<?php echo count($regiones);?>);
 <?php 
 	if($regiones && is_array($regiones)) {
		foreach($regiones as $key => $region) {
			echo 'regiones['.$key.'] = {"region_id":"'.$region['region_id'].'","region_name":"'.$region['region_name'].'","city_id":"'.$region['city_id'].'"};';
			echo "\n";
		}
	}
 ?>
 $('#shop_province').click(function(event) {
	 $('#shop_city').empty();
	 var provinceID = $(this).val();
	 var i = 0;
	 $('#shop_city').append('<option value="0">不限</option>');
	 for(i in cities) {
		 if(cities[i].province_id == provinceID) {
			 $('#shop_city').append('<option value="'+cities[i].city_id+'">'+cities[i].city_name+'</option>');
		 }
	 }
});
$('#shop_province').trigger('click');

$('#shop_city').click(function(event) {
	 $('#shop_region').empty();
	 var cityID = $(this).val();
	 var i = 0;
	 $('#shop_region').append('<option value="0">不限</option>');
	 for(i in regiones) {
		 if(regiones[i].city_id == cityID) {
			 $('#shop_region').append('<option value="'+regiones[i].region_id+'">'+regiones[i].region_name+'</option>');
		 }
	 }
});
$('#shop_city').trigger('click');

function searchArea() {
	var result_list = $('#search-form').serializeArray();
	$("#dg").datagrid('load',{
		shop_province: result_list[0].value,
		shop_city: result_list[1].value,
		shop_region: result_list[2].value,
		area_name:result_list[3].value,
		status:result_list[4].value
	});	
}

function searchKeyEventHandler(event) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (keyCode == 13) {
		if(event.type == 'keyup') {
			searchArea();	
		}
		return false
	}	
	return true;	
}			
//新增地段窗口
$('#ff_province').click(function(event) {
	 $('#ff_city').empty();
	 var provinceID = $(this).val();
	 var i = 0;
	 for(i in cities) {
		 if(cities[i].province_id == provinceID) {
			 $('#ff_city').append('<option value="'+cities[i].city_id+'">'+cities[i].city_name+'</option>');
		 }
	 }
});

$('#ff_city').click(function(event) {
	 $('#ff_region').empty();
	 var cityID = $(this).val();
	 var i = 0;
	 for(i in regiones) {
		 if(regiones[i].city_id == cityID) {
			 $('#ff_region').append('<option value="'+regiones[i].region_id+'">'+regiones[i].region_name+'</option>');
		 }
	 }
});
//$('#dlg').dialog({  
//    onClose:function(){  
//        $('#dg').datagrid('reload');  
//    }  
//});  

function newArea() {
	 document.getElementById("ff").reset();
	 document.getElementById("create_tip").style.display="none";
	 $('#ff_province').val(11);//广东省
	 $('#ff_province').trigger('click');
	 $('#ff_city').val(39);//广州市
	 $('#ff_city').trigger('click');
 	 $('#dlg').dialog('open').dialog('setTitle','添加地段');	
}

function submitForm(){  
	$.post($("#ff").attr("action"),$("#ff").serialize(),function(data) {
		$("#create_tip").text(data.info).show();
		if(data.success != 0) {
			$('#dg').datagrid('reload');  
		}
		setTimeout('document.getElementById("create_tip").style.display="none";',2000);
	});
}

//编辑地段窗口
$('#edit_ff_province').click(function(event) {
	 $('#edit_ff_city').empty();
	 var provinceID = $(this).val();
	 var i = 0;
	 for(i in cities) {
		 if(cities[i].province_id == provinceID) {
			 $('#edit_ff_city').append('<option value="'+cities[i].city_id+'">'+cities[i].city_name+'</option>');
		 }
	 }
});
//编辑地段窗口
$('#edit_ff_city').click(function(event) {
	 $('#edit_ff_region').empty();
	 var cityID = $(this).val();
	 var i = 0;
	 for(i in regiones) {
		 if(regiones[i].city_id == cityID) {
			 $('#edit_ff_region').append('<option value="'+regiones[i].region_id+'">'+regiones[i].region_name+'</option>');
		 }
	 }
});
function editArea() {
	document.getElementById("edit_tip").style.display="none";
	var row = $('#dg').datagrid('getSelected');
	if (row){	 
		$('#edit-ff').form('load',row);
		$('#edit_ff_province').trigger('click');
		$('#edit_ff_city').val(row.city_id);
		$('#edit_ff_city').trigger('click');
		$('#edit_ff_region').val(row.region_id);
		$('#edit-dlg').dialog('open').dialog('setTitle','编辑地段');	
	}
}

function submitEditForm(){  
	$.post($("#edit-ff").attr("action"),$("#edit-ff").serialize(),function(data) {
		$("#edit_tip").text(data.info).show();
		if(data.success != 0) {
			$('#dg').datagrid('reload');  
			setTimeout('document.getElementById("edit_tip").style.display="none"; $("#edit-dlg").dialog("close");  ',2000);			
		} else {
			setTimeout('document.getElementById("edit_tip").style.display="none";',2000);	
		}
	});
}
</script> 