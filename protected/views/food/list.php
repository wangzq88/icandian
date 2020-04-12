<style type="text/css">
#dlg table td,#edit-dlg table td{ padding:4px 0;}
#dlg tr td:first-child,#edit-dlg tr td:first-child{text-align:right; padding:0 10px 0 20px;}
#dlg tr td:first-child a,#edit-dlg  tr td:first-child a{cursor: help;border-bottom: 1px dotted #999999;text-decoration:none; color:inherit;}
</style>
<?php $timestamp = time();?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/datagrid-detailview.js"></script> 
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/uploadify/uploadify.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/uploadify/jquery.uploadify.min.js"></script>  
<table id="dg" style="height:600px;" title="美食管理 / 菜单管理" 
data-options="url:'/index.php?r=food/index',pagination:true,rownumbers:true,fitColumns:true,singleSelect:true,pageList:[10,15]"
  toolbar="#toolbar" >  
        <thead>  
            <tr>  
	            <th data-options="field:'food_id',hidden:true">ID</th>
                <th data-options="field:'food_name',align:'center'" width="15%">美食名称</th>  
                <th data-options="field:'food_img',hidden:true">美食图片</th>  
                <th data-options="field:'food_price',align:'center',formatter: function(value,row,index){return '￥'+value;}" width="10%">价格</th>
                <th data-options="field:'categories_id',hidden:true">美食分类ID</th>
                <th data-options="field:'categories_name',align:'center'" width="10%">美食分类</th>                 
                <th data-options="field:'is_new',hidden:true">新品</th>
                <th data-options="field:'is_new_text',align:'center'" width="5%">新品</th>
                <th data-options="field:'is_hot',hidden:true">加辣</th>
                <th data-options="field:'is_hot_text',align:'center'" width="5%">加辣</th>
                <th data-options="field:'is_facia',hidden:true">招牌</th>
                <th data-options="field:'is_facia_text',align:'center'" width="5%">招牌</th>
                 <th data-options="field:'is_book',hidden:true">预订</th>
                <th data-options="field:'is_book_text',align:'center'" width="5%">预订</th>                                                               
                <th data-options="field:'food_remark',align:'center'" width="15%">说明</th>  
                <th data-options="field:'flag',hidden:true">美食供应</th>
                <th data-options="field:'flag_text',align:'center'" width="15%">美食供应</th>
                <th data-options="field:'attribs',hidden:true">供应说明</th> 
                <th data-options="field:'attribs_text',align:'center'" width="15%">供应说明</th> 
                <th data-options="field:'ordering',align:'center'" width="5%">排序</th>  
            </tr>  
        </thead>  
    </table>
    <div id="toolbar" style="padding:10px;height:auto">  
	    <div style="margin-bottom:5px">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newFood()">新建美食</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editFood()">编辑美食</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyFood()">删除美食</a>  
        </div>
         <div> 
        <form style="display:inline-block; padding-left:5px;" action="/index.php?r=food/search" id="search-form">
        美食分类：<select name="categories_id" onkeyup="return searchKeyEventHandler(event);" onkeydown="return  searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
                            <option value="0">全部</option> 
                        	<?php foreach($categories as $cat): ?>
                        	<option value="<?php echo $cat['categories_id']; ?>"><?php echo $cat['categories_name']; ?></option> 
                            <?php endforeach; ?>
                    	</select>&nbsp;&nbsp;          
        美食名称：<input type="text" name="food_name" style="width:120px" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);"/>&nbsp;&nbsp;  
        加辣：<select name="is_hot" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        <option value="-1">----</option> 
        <option value="0">否</option> 
        <option value="1">是</option> 
        </select>&nbsp;&nbsp;  
        新品：<select name="is_new" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        <option value="-1">----</option> 
        <option value="0">否</option> 
        <option value="1">是</option> 
        </select>&nbsp;&nbsp;  
        招牌：<select name="is_facia" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        <option value="-1">----</option> 
        <option value="0">否</option> 
        <option value="1">是</option> 
        </select>&nbsp;&nbsp;       
        招牌：<select name="is_book" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        <option value="-1">----</option> 
        <option value="0">否</option> 
        <option value="1">是</option> 
        </select>&nbsp;&nbsp;              
                        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="searchFood()">搜索</a> 
        </form>
        </div>
        <div class="clear"></div>
    </div>     
    
 <!--新增分类窗口-->
<div id="dlg" class="easyui-dialog" style="width:600px;height:520px;padding:10px 20px"
    buttons="#dlg-buttons" data-options="maximizable:true,resizable:true,modal:true,closed:true">
<form id="ff" method="post" action="/index.php?r=food/create" enctype="multipart/form-data" target="frameFile"> 
	<input type="hidden" name="food_img_path" value="" id="add_food_img_path"/>
<div class="demo-info" style="display:none;">
		<div class="demo-tip icon-tip"></div>
		<div id="create_tip">菜式已经创建成功！</div>
	</div>    
<!--<div style="text-align:center;display:none;font-weight:bold;" id="create_tip">菜式已经创建成功！</div>-->
            <table>         
                <tr>  
                    <td>美食名称：</td>  
                    <td><input class="easyui-validatebox" type="text" name="food_name" data-options="required:true,validType:'length[2,30]'" id="ff-food_name"/></td>  
                </tr> 
<!--                <tr>  
                    <td>编号：</td>  
                    <td><input type="text" class="easyui-validatebox" name="alias" data-options="required:false,validType:'alpha',missingMessage:'可以不填'" placeholder="可以不填"/></td>  
                </tr>    -->             
                <tr>  
                    <td>价格：</td>  
                    <td><input class="easyui-numberbox" type="text" name="food_price" data-options="required:true,precision:1,groupSeparator:',',decimalSeparator:'.',prefix:'￥'" /></td>  
                </tr> 
                <tr>  
                    <td><a href="javascript:void(0);" title="你必须选择一个美食分类。如果没有分类，必须在<菜单分类管理>新建一个" class="easyui-tooltip">美食分类</a>：</td>  
                    <td>
                    	<select name="categories_id">
                        	<?php foreach($categories as $cat): ?>
                        	<option value="<?php echo $cat['categories_id']; ?>"><?php echo $cat['categories_name']; ?></option> 
                            <?php endforeach; ?>
                    	</select>&nbsp;&nbsp;&nbsp;&nbsp;<a href="/index.php?r=foodCategories/index" style="text-decoration:none;font-size:12px;">新建</a>
                    </td>  
                </tr>  
                <tr>  
                    <td>新品：</td>  
                    <td>
                    	<select name="is_new">
                        	<option value="0" selected="selected">否</option>
                            <option value="1">是</option>
                        </select>
                     </td>  
                </tr>
                <tr>  
                    <td>加辣：</td>  
                    <td>
                    	<select name="is_hot">
                        	<option value="0" selected="selected">否</option>
                            <option value="1">是</option>
                        </select>
                     </td>  
                </tr>
                <tr>  
                    <td>招牌：</td>  
                    <td>
                    	<select name="is_facia">
                        	<option value="0" selected="selected">否</option>
                            <option value="1">是</option>
                        </select>
                     </td>  
                </tr>        
 				<tr>  
                    <td><a href="javascript:void(0);" title="该美食是否接受预订" class="easyui-tooltip">预订</a>：</td>  
                    <td>
                    	<select name="is_book">
                            <option value="1" selected="selected">是</option>
                        	<option value="0">否</option>
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
                    <td><a href="javascript:void(0);" title="填一个大于0的数字，数字越大，美食显示就越靠前。可以不填" class="easyui-tooltip">排序</a>：</td>  
                    <td><input class="easyui-numberbox" type="text" name="ordering" data-options="missingMessage:'可以不填'" placeholder="可以不填" title="可以不填"/></td>  
                </tr>    
                <tr>  
                    <td><a href="javascript:void(0);" title="对该美食的描述，可以不填" class="easyui-tooltip">说明</a>：</td>  
                    <td><textarea name="food_remark" rows="3" cols="40" placeholder="可以不填"></textarea></td>  
                </tr>                     
                 <tr>  
                    <td><a href="javascript:void(0);" title="该美食的图片，可以不用上传" class="easyui-tooltip">图片</a>：</td>  
                    <td><input type="file" name="add_food_img" id="add_food_img"/></td>  
                </tr>                                                                            
               </table>
               </form>        
</div>        
<div id="dlg-buttons">
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitForm();">保存</a>
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a>
</div>           
<iframe id="frameFile" name="frameFile" style="display:none"></iframe>
 <!--编辑分类窗口-->
<div id="edit-dlg" class="easyui-dialog" style="width:600px;height:520px;padding:10px 20px"
      buttons="#edit-dlg-buttons" data-options="maximizable:true,resizable:true,modal:true,closed:true">
<form id="edit-ff" method="post" enctype="multipart/form-data"  action="/index.php?r=food/update" target="frameFile"> 
<input type="hidden" name="food_id" />
<input type="hidden" name="food_img_path" value="" id="edit_food_img_path"/>
<div class="demo-info" style="display:none;">
		<div class="demo-tip icon-tip"></div>
		<div id="edit_tip">美食修改成功！</div>
	</div>   
<!--<div id="edit_tip" style="display:none;text-align:center;font-weight:bold;">菜式修改成功！</div>-->
       <table>  
                <tr>  
                    <td>美食名称：</td>  
                    <td><input class="easyui-validatebox" type="text" name="food_name" data-options="required:true,validType:'length[2,30]'" /></td>  
                </tr> 
<!--                <tr>  
                    <td>编号：</td>  
                    <td><input type="text" class="easyui-validatebox" name="alias" data-options="required:false,validType:'alpha',missingMessage:'可以不填'" placeholder="可以不填"/></td>  
                </tr>    -->                
                <tr>  
                    <td>价格：</td>  
                    <td><input class="easyui-numberbox" id="edit_food_price" type="text" name="food_price" data-options="required:true,precision:1,groupSeparator:',',decimalSeparator:'.',prefix:'￥'" /></td>  
                </tr>  
                <tr>  
                    <td><a href="javascript:void(0);" title="你必须选择一个美食分类。如果没有分类，必须在<菜单分类管理>新建一个" class="easyui-tooltip">美食分类</a>：</td>  
                    <td>
                    	<select name="categories_id">
                        	<?php foreach($categories as $cat): ?>
                        	<option value="<?php echo $cat['categories_id']; ?>"><?php echo $cat['categories_name']; ?></option> 
                            <?php endforeach; ?>
                    	</select>
                    </td>  
                </tr>      
                <tr>  
                    <td>新品：</td>  
                    <td>
                    	<select name="is_new">
                        	<option value="0" selected="selected">否</option>
                            <option value="1">是</option>
                        </select>
                     </td>  
                </tr>
                <tr>  
                    <td>加辣：</td>  
                    <td>
                    	<select name="is_hot">
                        	<option value="0" selected="selected">否</option>
                            <option value="1">是</option>
                        </select>
                     </td>  
                </tr>              
                <tr>  
                    <td>招牌：</td>  
                    <td>
                    	<select name="is_facia">
                        	<option value="0" selected="selected">否</option>
                            <option value="1">是</option>
                        </select>
                     </td>  
                </tr> 
                <tr>  
                    <td><a href="javascript:void(0);" title="该美食是否接受预订" class="easyui-tooltip">预订</a>：</td>  
                    <td>
                    	<select name="is_book">
                            <option value="1" selected="selected">是</option>
                        	<option value="0">否</option>
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
                    <td><a href="javascript:void(0);" title="填一个大于0的数字，数字越大，美食显示就越靠前。可以不填" class="easyui-tooltip">排序</a>：</td>  
                    <td><input class="easyui-numberbox" id="edit_ordering" type="text" name="ordering" data-options="missingMessage:'可以不填'" placeholder="可以不填" title="可以不填"/></td>  
                </tr>
                <tr>  
                    <td><a href="javascript:void(0);" title="对该美食的描述，可以不填" class="easyui-tooltip">说明</a>：</td>  
                    <td><textarea name="food_remark" rows="3" cols="40" placeholder="可以不填"></textarea></td>  
                </tr>                     
                 <tr>  
                    <td><a href="javascript:void(0);" title="该美食的图片，可以不用上传" class="easyui-tooltip">图片</a>：</td>  
                    <td><input type="file" name="edit_food_img"  id="edit_food_img"/></td>  
                </tr>                                                      
               </table>
               
               </form>        
</div>        
<div id="edit-dlg-buttons">
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitEditForm();">保存</a>
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#edit-dlg').dialog('close')">取消</a>
</div>    
<style type="text/css">  
.datagrid-row-detail{ padding:15px 0;}
.c-item{ margin-bottom:10px;}
.c-label{display:inline-block;width:100px;font-weight:bold;text-align:left;}  
.c-content {display:inline-block;}
</style>  
 <script type="text/javascript">  

 //选中文字
 var funGetSelected = function(element) {
    if (!window.getSelection) { 
        //IE浏览器
        return document.selection.createRange().text;
    } else {
        return element.value.substr(element.selectionStart, element.selectionEnd - element.selectionStart);
    }
}, funTextAsTopic = function(textObj, textFeildValue) {
    textObj.focus();
    if (textObj.createTextRange) {
        var caretPos = document.selection.createRange().duplicate();
        document.selection.empty();
        caretPos.text = textFeildValue;
    } else if (textObj.setSelectionRange) {
        var rangeStart = textObj.selectionStart;
        var rangeEnd = textObj.selectionEnd;
        var tempStr1 = textObj.value.substring(0, rangeStart);
        var tempStr2 = textObj.value.substring(rangeEnd);
        textObj.value = tempStr1 + textFeildValue + tempStr2;
        textObj.blur();
    }
};

 $(".idingcan_flag").change(function(event,val) {
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
				value: 1
			},{
				label: '星期二',
				value: 2
			},{
				label: '星期三',
				value: 3
			},{
				label: '星期四',
				value: 4
			},{
				label: '星期五',
				value: 5
			},{
				label: '星期六',
				value: 6
			},{
				label: '星期日',
				value: 7
			}],			
			multiple:true 
		});  	
		if(val && val.length > 0)	{
			val = val.split(',');
			$('#cc').combobox('setValues',val); 
		}
	 }
 });
 
 function resetNewFoodForm() {
	$("#add_food_img_path").val('');
	$("#add_food_pic").remove();		 
 }
 
 function newFood() {
//	 document.getElementById("create_tip").style.display="none";
	 jQuery("#create_tip").closest(".demo-info").hide();
	 resetNewFoodForm();	 
 	 $('#dlg').dialog('open').dialog('setTitle','新建美食');
//	var oTextarea = document.getElementById("ff-food_name");
//	var textSelection = funGetSelected(oTextarea);
//    if (textSelection) {
//		 funTextAsTopic(oTextarea, textSelection);
//    }	
//	$('#ff').form('clear');
 }
 
 function editFood() {
	 //document.getElementById("edit_tip").style.display="none";
	 jQuery("#edit_tip").closest(".demo-info").hide();
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#edit-dlg').dialog('open').dialog('setTitle','编辑美食');
		$('#edit-ff').find('input[name="food_id"]').val(row.food_id);
		$('#edit-ff').find('input[name="food_name"]').val(row.food_name);
//		$('#edit-ff').find('input[name="alias"]').val(row.alias);
		$('#edit_food_price').numberbox('setValue',row.food_price);
		$('#edit-ff').find('select[name="categories_id"]').val(row.categories_id);
		$('#edit-ff').find('select[name="is_hot"]').val(row.is_hot);
		$('#edit-ff').find('select[name="is_new"]').val(row.is_new);
		$('#edit-ff').find('select[name="is_facia"]').val(row.is_facia);
		$('#edit-ff').find('select[name="is_book"]').val(row.is_book);
		$('#edit-ff').find('input[name="ordering"]').val(row.ordering);
		$('#edit_ordering').numberbox('setValue',row.ordering);
		$('#edit-ff').find('select[name="flag"]').val(row.flag);
		$('#edit-ff').find('textarea[name="food_remark"]').val(row.food_remark);
		$("#edit_food_pic").remove();
		$("#edit_food_img").before('<img src="'+row.food_img+'" width="100" height="100" id="edit_food_pic"/>');		
		$(".idingcan_flag").trigger('change',[row.attribs]);
	} else {
		 $.messager.show({  
                title:'提示',  
                msg:'请选中要编辑的美食',  
				timeout:5000,
                showType:'fade',  
                style:{  
                    right:'',  
                    bottom:''  
                }  
           });  
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
	var result_list = $('#search-form').serializeArray();
	$("#dg").datagrid('load',{
		categories_id: result_list[0].value,
		food_name: result_list[1].value,
		is_hot:result_list[2].value,
		is_new:result_list[3].value,
		is_facia:result_list[4].value,
		is_book:result_list[5].value
	});
}
		
function searchKeyEventHandler(event) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
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
 
$('#dg').datagrid({  
  view:detailview,
  detailFormatter: function(rowIndex, rowData){  		
	  var html = '';
	  var picture = $.trim(rowData.food_img) != '' ? rowData.food_img:'<?php echo FOOD_DEFAULT_LOGO;?>';
	  html += '<img src="'+picture+'" style="width:150px;float:left;">';
	  html += '<div style="float:left;margin-left:20px;">'+
	  '<div class="c-item"><span class="c-label">美食名称:</span> <span class="c-content">'+rowData.food_name+'</span></div>'+
	  '<div class="c-item"><span class="c-label">价格:</span>  <span class="c-content">￥'+rowData.food_price+'</span></div>'+
	  '<div class="c-item"><span class="c-label">新品:</span> <span class="c-content"> '+rowData.is_new_text+'</span></div>'+
	  '<div class="c-item"><span class="c-label">加辣:</span> <span class="c-content"> '+rowData.is_hot_text+'</span></div>'+
	  '<div class="c-item"><span class="c-label">招牌:</span> <span class="c-content"> '+rowData.is_facia_text+'</span></div>'+
	  '<div class="c-item"><span class="c-label">预订:</span> <span class="c-content"> '+rowData.is_book_text+'<span></div>'+
	  '<div class="c-item"><span class="c-label">说明:</span>  <span class="c-content">'+rowData.food_remark+'</span></div>'+
	  '<div class="c-item"><span class="c-label">美食供应:</span>  <span class="c-content">'+rowData.flag_text+'</span></div>'+
	  '<div class="c-item"><span class="c-label">供应说明:</span>  <span class="c-content">'+rowData.attribs_text+'</span></div>'+
	  '<div class="c-item"><span class="c-label">美食分类:</span>  <span class="c-content">'+rowData.categories_name+'</span></div>'+
	  '<div class="c-item"><span class="c-label">排序:</span> <span class="c-content"> '+rowData.ordering+'</span></div></div>';
	  html += '<div class="clear"></div>';
	  return html;  
  }/*,
  rowStyler: function(index,row){
	 if ((index+1)%2 == 0){
		return 'background-color:#F0F0F6;color:#3D3D3D;';
	 }
  }  */
});  

$('#add_food_img').uploadify({
	'formData'     : {
		'timestamp' : '<?php echo $timestamp;?>',
		'token'     : '<?php echo md5('unique_salt' . $timestamp);?>',
		'shop_id' : '<?php echo Yii::app()->user->shop_id;?>'
	},
	'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg',
	'fileSizeLimit' : '1MB',
	'buttonText':'选择图片',
	'swf'      : '/assets/uploadify/uploadify.swf',
	'uploader' : '/api/foodUploadify.php',
 	'onUploadSuccess' : function(file, data, response) {
		var obj = $.parseJSON(data);
		if(obj.success) {
			$("#add_food_img_path").val(obj.food_img);
			$("#add_food_pic").remove();
			$("#add_food_img").before('<img src="'+obj.food_img+'" width="100" height="100" id="add_food_pic"/>');
		} else {
			 show_friendly_tip(obj.info,$('#ff'),2); 
		}
     }	
});

$('#edit_food_img').uploadify({
	'formData'     : {
		'timestamp' : '<?php echo $timestamp;?>',
		'token'     : '<?php echo md5('unique_salt' . $timestamp);?>',
		'shop_id' : '<?php echo Yii::app()->user->shop_id;?>'
	},
	'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg',
	'fileSizeLimit' : '1MB',
	'buttonText':'选择图片',
	'swf'      : '/assets/uploadify/uploadify.swf',
	'uploader' : '/api/foodUploadify.php',
 	'onUploadSuccess' : function(file, data, response) {
		var obj = $.parseJSON(data);
		if(obj.success) {
			$("#edit_food_img_path").val(obj.food_img);
			$("#edit_food_pic").remove();
			$("#edit_food_img").before('<img src="'+obj.food_img+'" width="100" height="100" id="edit_food_pic"/>');
		} else {
			 show_friendly_tip(obj.info,$('#edit-ff'),2); 
		}
     }	
});
</script>           