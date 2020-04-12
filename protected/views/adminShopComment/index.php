<style type="text/css">
.label{text-align:right;}
#dlg table td,#edit-dlg table td{ padding:4px 0;}
#dlg tr td:first-child,#edit-dlg tr td:first-child{text-align:right; padding:0 10px 0 20px;}
#dlg tr td:first-child a,#edit-dlg  tr td:first-child a{cursor: help;border-bottom: 1px dotted #999999;text-decoration:none; color:inherit;}
.datagrid-row-detail{ padding:15px 0;}
.c-item{ margin-bottom:10px;font-size:14px;}
.c-label{display:inline-block;width:10%;font-weight:bold;text-align:left;vertical-align: top;}  
.c-content {display:inline-block;width: 80%;}
</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/datagrid-detailview.js"></script> 
<table id="dg" style="height:500px" data-options="url:'/index.php?r=adminShopComment/index',toolbar:'#toolbar',pagination:true,rownumbers:true,fitColumns:true,singleSelect:false,pageList:[10,15]">  
	<thead>  
		<tr>  
        	<th data-options="field:'ck',checkbox:true"></th>
            <th data-options="field:'id',align:'center'" width="5%">ID</th>
        	<th data-options="field:'uid',align:'center',hidden:true">uid</th>
			<th data-options="field:'username',align:'center'" width="10%">用户名</th>  
            <th data-options="field:'avatar',hidden:true">头像</th>  
            <th data-options="field:'content',align:'center'" width="30%">评论内容</th>
            <th data-options="field:'shop_id',align:'center'" width="5%">餐店ID</th>
            <th data-options="field:'parent_id',align:'center'" width="5%">父帖</th>
            <th data-options="field:'status',hidden:true">显示</th>
            <th data-options="field:'status_text',align:'center'" width="5%">显示</th>
            <th data-options="field:'timestamp',align:'center'" width="15%">发表时间</th>  
		</tr>  
	</thead>  
</table>
<div id="toolbar" style="padding:10px;height:auto">
<div style="margin-bottom:5px"> 
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" title="标记为显示" onclick="setStatusFlag(1)">显示</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" title="标记为未显示" onclick="setStatusFlag(0)">不显示</a> 
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="deleteShopComment()">删除</a> 
        </div>
         <div> 
<form style="display:inline-block;padding-left:5px;" action="/index.php?r=adminShopComment/already" id="search-form">
        用户名：<input type="text" name="username" style="width:120px" onkeyup="return enterKeyEventHandler(event,searchShopComment);" />&nbsp;  
        显示：<select name="status" onkeyup="return enterKeyEventHandler(event,searchShopComment);" >
        	<option value="-1">——</option>
            <option value="1">是</option>
        	<option value="0">否</option>
        </select>&nbsp;  
		发表时间： <input class="easyui-datebox" style="width:100px" name="timestamp[]" />  
      — <input class="easyui-datebox" style="width:100px"  name="timestamp[]"/>    &nbsp;       
<a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-search" onclick="searchShopComment()">搜索</a> 
</form>        
</div>
<div class="clear"></div>
</div>
<script type="text/javascript">
$('#dg').datagrid({  
  view:detailview,
  detailFormatter: function(rowIndex, rowData){  		
	  var html = '';
  	  html += '<div style="float:left;width: 100%;">'+
	  '<div class="c-item"><span class="c-label">ID:</span> <span class="c-content">'+rowData.id+'</span></div>'+	  	  
	  '<div class="c-item"><span class="c-label">用户ID:</span> <span class="c-content">'+rowData.uid+'</span></div>'+	  
	  '<div class="c-item"><span class="c-label">用户名:</span> <span class="c-content">'+rowData.username+'</span></div>'+
	  '<div class="c-item"><span class="c-label">评论内容:</span>  <span class="c-content">'+rowData.content+'</span></div>'+
	  '<div class="c-item"><span class="c-label">父帖:</span> <span class="c-content"> '+rowData.parent_id+'</span></div>'+
	  '<div class="c-item"><span class="c-label">显示:</span> <span class="c-content"> '+rowData.status_text+'<span></div>'+
	  '<div class="c-item"><span class="c-label">发表时间:</span>  <span class="c-content">'+rowData.timestamp+'</span></div>';
	  html += '</div><div class="clear"></div>';
	  return html;  
  }  
});  

function searchShopComment() {
	var result_list = $('#search-form').serializeArray();
	$("#dg").datagrid('load',{
		username: result_list[0].value,
		status:result_list[1].value,
		"add_time[0]":result_list[2].value,
		"add_time[1]":result_list[3].value		
	});	
}

function deleteShopComment() {
	var rows = $('#dg').datagrid('getChecked');
	if (rows){
		$.messager.confirm('确认','一旦删除将不能恢复，你确定要删除选中的记录吗？',function(r){
			if (r){
				var ids = new Array();
				for(var i = 0; i < rows.length; i++) {
					ids[i] =  rows[i].id;
				}
				ids = ids.join(',');		
				$.post('/index.php?r=adminShopComment/delete',{id:ids},function(result){
					$('#dg').datagrid('reload'); 	// reload the user data
				},'json');
			}
		});
	}	
}

function setStatusFlag(status) {
	var rows = $('#dg').datagrid('getChecked');
	if (rows){
		var ids = new Array();
		for(var i = 0; i < rows.length; i++) {
			ids[i] =  rows[i].id;
		}
		ids = ids.join(',');
		$.post('/index.php?r=adminShopComment/update',{id:ids,status:status},function(result){
			$('#dg').datagrid('reload'); 	// reload the user data
		},'json');
	}			
}
</script>