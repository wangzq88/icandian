<table id="dg" style="height:600px;"  
            url="/index.php?r=food/index"  
            toolbar="#toolbar" pagination="true"  frozenColumns=""
            rownumbers="true" fitColumns="true" singleSelect="true">  
        <thead>  
            <tr>  
	            <th data-options="field:'food_id',hidden:true">ID</th>
                <th data-options="field:'food_name',width:100,align:'center'">餐式名称</th>  
                <th data-options="field:'alias',width:50,align:'center'">编号</th>
                <th data-options="field:'food_img',hidden:true">餐式图片</th>  
                <th data-options="field:'food_price',width:80,align:'center',formatter: function(value,row,index){return '￥'+value;}">价格</th>
                <th data-options="field:'is_new',hidden:true">是否新品</th>
                <th data-options="field:'is_new_text',width:100,align:'center'">是否新品</th>
                <th data-options="field:'is_hot',hidden:true">是否加辣</th>
                <th data-options="field:'is_hot_text',width:100,align:'center'">是否加辣</th>                                
                <th data-options="field:'food_remark',width:150,align:'center'">备注</th>  
                <th data-options="field:'flag',hidden:true">餐式供应</th>
                <th data-options="field:'flag_text',width:100,align:'center'">餐式供应</th>
                 <th data-options="field:'attribs',hidden:true">供应说明</th> 
                <th data-options="field:'attribs_text',width:100,align:'center'">供应说明</th> 
                <th data-options="field:'categories_id',hidden:true">餐式分类ID</th>
                <th data-options="field:'categories_name',width:80,align:'center'">餐式分类</th>  
                <th data-options="field:'ordering',width:50,align:'center'">排序</th>  
            </tr>  
        </thead>  
    </table>
    <div id="toolbar" style="padding:5px 10px;">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newFood()">新建餐式</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editFood()">编辑餐式</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyFood()">删除餐式</a>  
        <form style="display:inline-block;float:right;" action="/index.php?r=food/search" id="search-form">
        餐式分类：<select name="categories_id" onkeyup="return searchKeyEventHandler(event);" onkeydown="return  searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
                            <option value="0">全部</option> 
                        	<?php foreach($categories as $cat): ?>
                        	<option value="<?php echo $cat['categories_id']; ?>"><?php echo $cat['categories_name']; ?></option> 
                            <?php endforeach; ?>
                    	</select>&nbsp;        
        餐式名称：<input type="text" name="food_name" style="width:120px" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);"/>&nbsp;
        加辣：<select name="is_hot" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        <option value="-1">----</option> 
        <option value="0">否</option> 
        <option value="1">是</option> 
        </select>&nbsp;
        新品：<select name="is_new" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        <option value="-1">----</option> 
        <option value="0">否</option> 
        <option value="1">是</option> 
        </select>&nbsp;
                        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="searchFood()">搜索</a> 
        </form>
    </div>     
    
 <!--新增分类窗口-->
<div id="dlg" class="easyui-dialog" style="width:600px;height:450px;padding:10px 20px"
        closed="true" buttons="#dlg-buttons">
        <div style="text-align:center;display:none;font-weight:bold;" id="create_tip">菜式已经创建成功！</div>
<form id="ff" method="post" action="/index.php?r=food/create" enctype="multipart/form-data" target="frameFile"> 
            <table>  
                <tr>  
                    <td>餐式名称：</td>  
                    <td><input class="easyui-validatebox" type="text" name="food_name" data-options="required:true,validType:'length[2,30]'" /></td>  
                </tr> 
                <tr>  
                    <td>编号：</td>  
                    <td><input type="text" class="easyui-validatebox" name="alias" data-options="required:false,validType:'alpha'"/></td>  
                </tr>                 
                <tr>  
                    <td>价格：</td>  
                    <td><input class="easyui-numberbox" type="text" name="food_price" data-options="required:true,precision:1,groupSeparator:',',decimalSeparator:'.',prefix:'￥'" /></td>  
                </tr> 
                <tr>  
                    <td>餐式分类：</td>  
                    <td>
                    	<select name="categories_id">
                        	<?php foreach($categories as $cat): ?>
                        	<option value="<?php echo $cat['categories_id']; ?>"><?php echo $cat['categories_name']; ?></option> 
                            <?php endforeach; ?>
                    	</select>
                    </td>  
                </tr>  
                <tr>  
                    <td>是否新品：</td>  
                    <td>
                    	<select name="is_new">
                        	<option value="0" selected="selected">否</option>
                            <option value="1">是</option>
                        </select>
                     </td>  
                </tr>
                <tr>  
                    <td>是否加辣：</td>  
                    <td>
                    	<select name="is_hot">
                        	<option value="0" selected="selected">否</option>
                            <option value="1">是</option>
                        </select>
                     </td>  
                </tr>                                                                 
                <tr>  
                    <td>供应设置：</td>  
                    <td>
                    	<select name="flag" class="idingcan_flag">
                        	<option value="1" selected="selected">每天供应</option>
                            <option value="2">按周供应</option>
                            <option value="3">按月供应</option>
                        </select>
                     </td>  
                </tr>   
                <tr style="display:none;">  
                    <td>供应说明：</td>  
                    <td>                    
                        
                     </td>  
                </tr>    
                <tr>  
                    <td>排序：</td>  
                    <td><input class="easyui-numberbox" type="text" name="ordering" data-options="required:true" /></td>  
                </tr>    
                <tr>  
                    <td>备注：</td>  
                    <td><textarea name="food_remark" rows="3" cols="40"></textarea></td>  
                </tr>                     
                 <tr>  
                    <td>图片：</td>  
                    <td><input type="file" name="food_img"  /></td>  
                </tr>                                                                              
               </table>
               </form>        
</div>        
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitForm();">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a>
</div>           
<iframe id="frameFile" name="frameFile" style="display:none"></iframe>
 <!--编辑分类窗口-->
<div id="edit-dlg" class="easyui-dialog" style="width:600px;height:450px;padding:10px 20px"
        closed="true" buttons="#edit-dlg-buttons">
<form id="edit-ff" method="post" enctype="multipart/form-data"  action="/index.php?r=food/update" target="frameFile"> 
<div id="edit_tip" style="display:none;text-align:center;font-weight:bold;">菜式修改成功！</div>
<input type="hidden" name="food_id" />
       <table>  
                <tr>  
                    <td>餐式名称：</td>  
                    <td><input class="easyui-validatebox" type="text" name="food_name" data-options="required:true,validType:'length[2,30]'" /></td>  
                </tr> 
                <tr>  
                    <td>编号：</td>  
                    <td><input type="text" class="easyui-validatebox" name="alias" data-options="required:false,validType:'alpha'"/></td>  
                </tr>                    
                <tr>  
                    <td>价格：</td>  
                    <td><input class="easyui-numberbox" type="text" name="food_price" data-options="required:true,precision:1,groupSeparator:',',decimalSeparator:'.',prefix:'￥'" /></td>  
                </tr>  
                <tr>  
                    <td>餐式分类：</td>  
                    <td>
                    	<select class="easyui-combobox" name="categories_id">
                        	<?php foreach($categories as $cat): ?>
                        	<option value="<?php echo $cat['categories_id']; ?>"><?php echo $cat['categories_name']; ?></option> 
                            <?php endforeach; ?>
                    	</select>
                    </td>  
                </tr>      
                <tr>  
                    <td>是否新品：</td>  
                    <td>
                    	<select name="is_new">
                        	<option value="0" selected="selected">否</option>
                            <option value="1">是</option>
                        </select>
                     </td>  
                </tr>
                <tr>  
                    <td>是否加辣：</td>  
                    <td>
                    	<select name="is_hot">
                        	<option value="0" selected="selected">否</option>
                            <option value="1">是</option>
                        </select>
                     </td>  
                </tr>                                          
                <tr>  
                    <td>供应设置：</td>  
                    <td>
                    	<select name="flag" class="idingcan_flag">
                        	<option value="1" selected="selected">每天供应</option>
                            <option value="2">按周供应</option>
                            <option value="3">按月供应</option>
                        </select>
                     </td>   
                </tr>   
                <tr style="display:none;">  
                    <td>供应说明：</td>  
                    <td>                    
                        
                     </td>  
                </tr>     
                <tr>  
                    <td>排序：</td>  
                    <td><input class="easyui-numberbox" type="text" name="ordering" data-options="required:true" /></td>  
                </tr>
                <tr>  
                    <td>备注：</td>  
                    <td><textarea name="food_remark" rows="5" cols="40"></textarea></td>  
                </tr>                     
                 <tr>  
                    <td>图片：</td>  
                    <td><input type="file" name="food_img"  /></td>  
                </tr>                                                      
               </table>
               </form>        
</div>        
<div id="edit-dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitEditForm();">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#edit-dlg').dialog('close')">取消</a>
</div>    
    <style type="text/css">  
        .c-label{  
            display:inline-block;  
            width:100px;  
            font-weight:bold;  
        }  
    </style>  
 <script>  
         var cardview = $.extend({}, $.fn.datagrid.defaults.view, {  
            renderRow: function(target, fields, frozen, rowIndex, rowData){  
				var fidlds_hide = ['flag','attribs',"categories_id","food_id","food_img","is_new","is_hot"];
                var cc = [];  
                cc.push('<td colspan=' + fields.length + ' style="padding:10px 5px;border:0;">');  
                if (!frozen){   
                    cc.push('<img src="' + rowData.food_img + '" style="width:150px;float:left;">');  
                    cc.push('<div style="float:left;margin-left:20px;">');  
					var copts = '';
                    for(var i=0; i<fields.length; i++){  
						if($.inArray(fields[i],fidlds_hide) == -1) {
                        	copts = $(target).datagrid('getColumnOption', fields[i]);  
							if(fields[i] == 'food_price')
								cc.push('<p><span class="c-label">' + copts.title + ':</span> ￥' + rowData[fields[i]] + '</p>');  
							else
                        		cc.push('<p><span class="c-label">' + copts.title + ':</span> ' + rowData[fields[i]] + '</p>');  
						}
                    }  
                    cc.push('</div>');  
                }  
                cc.push('</td>');  
                return cc.join('');  
            }  
        });  
            $('#dg').datagrid({  
                view: cardview 				  
            });  
 
 $(".idingcan_flag").click(function(event,val) {
	 if($(this).val() == 1) {
		 $(this).parents('tr').next('tr').hide();
	 } else if($(this).val() == 3) {
		 $(this).parents('tr').next('tr').show();
		 $(this).parents('tr').next('tr').find('td:last').empty().append('<select name="attribs[]" ><option value="1-10"  selected="selected">每月1-10号供应</option> <option value="11-20">每月11-20号供应</option> <option value="21-31">每月21-31号供应</option><option value="1-15">每月1-15号供应</option><option value="16-31">每月16-31号供应</option></select>');
	 } else if($(this).val() == 2){
		 $('#cc').remove();
		 $(this).parents('tr').next('tr').show();
		 $(this).parents('tr').next('tr').find('td:last').empty().append('<input id="cc" name="attribs[]" value="" />');
		$('#cc').combobox({  
			valueField: 'value',
			textField: 'label',		
			required:true,
			editable:false,
			data: [{
				label: '星期一',
				value: '1'
			},{
				label: '星期二',
				value: '2'
			},{
				label: '星期三',
				value: '3'
			},{
				label: '星期四',
				value: '4'
			},{
				label: '星期五',
				value: '5'
			},{
				label: '星期六',
				value: '6'
			},{
				label: '星期日',
				value: '0'
			}],			
			multiple:true 
		});  		 	
		if($.trim(val))	{
			val = val.split(',');
			$('#cc').combobox('setValues', val); 
		}
	 }
 });
 function newFood() {
	 document.getElementById("create_tip").style.display="none";
 	$('#dlg').dialog('open').dialog('setTitle','新建美食');
//	$('#ff').form('clear');
 }
 
 function editFood() {
	 document.getElementById("edit_tip").style.display="none";
	var row = $('#dg').datagrid('getSelected');
	if (row){
/*		var str = '{';
		$.each(row, function(index, value) { 
			str += '"'+index + '":"' + $.trim(value)+'",'; 
		});
		str = str.substr(0,str.length-1);console.log($.isPlainObject(row));	
		str = str + '}';*/
		$('#edit-dlg').dialog('open').dialog('setTitle','编辑美食');
		$('#edit-ff').form('load',row);
		$("#edit-ff .idingcan_flag").trigger('click',[row.attribs]);
	}	 
 }
        function submitForm(){  
			$('#ff').submit();
//			$.post($("#ff").attr("action"),$("#ff").serialize(),function(data) {
//				if(data.success == 0)
//					$.messager.alert('提示',data.info,'warning');
//				else {
//					$('#dlg').dialog('close');
//					$('#dg').datagrid('reload'); 
//				}
//			});
		}
		
		function submitEditForm() {
			$('#edit-ff').submit();
//			$.post($("#edit-ff").attr("action"),$("#edit-ff").serialize(),function(data) {
//				if(data.success == 0)
//					$.messager.alert('提示',data.info,'warning');
//				else {
//					$('#edit-dlg').dialog('close');
//					$('#dg').datagrid('reload'); 
//				}
//			});			
		}

	function destroyFood(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('确认','一旦删除将不能恢复，你确定要删除选中的记录吗？',function(r){
					if (r){
						$.post('/index.php?r=food/delete',{food_id:row.food_id},function(result){
							$('#dg').datagrid('reload'); 	// reload the user data
						},'json');
					}
				});
			}
		}	
		
		function searchFood() {
			//console.log();
			var result_list = $('#search-form').serializeArray();
			$("#dg").datagrid('load',{
				categories_id: result_list[0].value,
				food_name: result_list[1].value,
				is_hot:result_list[2].value,
				is_new:result_list[3].value
			});
		}
		
		function searchKeyEventHandler(event) {//console.log(event.type);
			var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;//console.log(keyCode);
			if (keyCode == 13) {
				if(event.type == 'keyup') {
					searchFood();	
				}
				return false
			}	
			return true;	
		}			
$.extend($.fn.validatebox.defaults.rules, {  
    alpha: {  
        validator: function(value, param){  
            return !$.trim(value) || value.match(/^[A-Za-z0-9]+$/g);  
        },  
        message: '只能由字母和数字组成.'  
    }  
});  		
    </script>           