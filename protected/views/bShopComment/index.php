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
<table id="dg" style="height:500px" data-options="url:'/index.php?r=bShopComment/index',toolbar:'#toolbar',pagination:true,rownumbers:true,fitColumns:true,singleSelect:false,pageList:[10,15]">  
	<thead>  
		<tr>  
            <th data-options="field:'id',align:'center',hidden:true" width="5%">ID</th>
        	<th data-options="field:'uid',align:'center',hidden:true">uid</th>
			<th data-options="field:'username',align:'center'" width="10%">用户名</th>  
            <th data-options="field:'avatar',hidden:true">头像</th>  
            <th data-options="field:'content',align:'center'" width="30%">评论内容</th>
            <th data-options="field:'is_replay_text',align:'center'" width="5%">已经回复</th>
            <th data-options="field:'timestamp',align:'center'" width="15%">评论时间</th>  
		</tr>  
	</thead>  
</table>
<div id="toolbar" style="padding:10px;height:auto">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="replayContentView()">回复</a> 
<form style="display:inline-block;float:right;" action="/index.php?r=bShopComment/already" id="search-form">
        用户名：<input type="text" name="username" style="width:120px" onkeyup="return enterKeyEventHandler(event,searchShopComment);" />&nbsp;  
        显示：<select name="status" onkeyup="return enterKeyEventHandler(event,searchShopComment);" >
        	<option value="-1">——</option>
            <option value="1">是</option>
        	<option value="0">否</option>
        </select>&nbsp;  
		评论时间： <input class="easyui-datebox" style="width:100px" name="timestamp[]" />  
      — <input class="easyui-datebox" style="width:100px"  name="timestamp[]"/>    &nbsp;       
<a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-search" onclick="searchShopComment()">搜索</a> 
</form>        
<div class="clear"></div>
</div>
 <!--回复窗口-->
<div style="width:550px;height:450px;padding:10px 20px" id="dlg"  class="easyui-dialog" data-options="maximizable:false,resizable:false,modal:true,closed:true,draggable:false,title:'回复'" buttons="#message-dlg-buttons">
<form id="shop-comment-ff" method="post" action="/index.php?r=bShopComment/replay">  
<input type="hidden" name="id" value="" id="comment-id"/>
 <table>         
 	<tr>
    	<td style="font-weight:normal;">评论者：</td>
        <td><span id="replay-username"></span></td>
    </tr>
 	<tr>
    	<td style="font-weight:normal;">评论时间：</td>
        <td><span id="replay-timestamp"></span></td>
    </tr>    
    <tr>
	    <td style="vertical-align:top;font-weight:normal;">评论内容：</td>
    	<td><span id="comment-content"></span></td>
    </tr>
    <tr>
	    <td style="vertical-align:top;font-weight:normal;">输入回复：</td>
    	<td><textarea name="content" rows="6" cols="35" id="replay-content"></textarea></td>
    </tr>    
 </table>       
</form>
</div>        
<div id="message-dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitReplayAction()">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlg').dialog('close');">取消</a>
</div>   
<script type="text/javascript">
$('#dg').datagrid({  
  view:detailview,
  detailFormatter: function(rowIndex, rowData){  		
	  var html = '';
  	  html += '<div style="float:left;width: 100%;">'+	  
	  '<div class="c-item"><span class="c-label">用户名:</span> <span class="c-content">'+rowData.username+'</span></div>'+
	  '<div class="c-item"><span class="c-label">评论内容:</span>  <span class="c-content">'+rowData.content+'</span></div>'+
	  '<div class="c-item"><span class="c-label">评论时间:</span>  <span class="c-content">'+rowData.timestamp+'</span></div>';
	  if(rowData.is_replay) {
	  	html += '<div class="c-item"><span class="c-label">您的回复:</span>  <span class="c-content">'+rowData.replay_list.content+'</span></div>';
		html += '<div class="c-item"><span class="c-label">回复时间:</span>  <span class="c-content">'+rowData.replay_list.timestamp+'</span></div>';		
	  }
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
				$.post('/index.php?r=bShopComment/delete',{id:ids},function(result){
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
		$.post('/index.php?r=bShopComment/update',{id:ids,status:status},function(result){
			$('#dg').datagrid('reload'); 	// reload the user data
		},'json');
	}			
}

function submitReplayAction(){  
	if($.trim($("#replay-content").val()) != '') return false;
	$.post('/index.php?r=bShopComment/replay',$("#shop-comment-ff").serialize(),function(result){
		$('#dg').datagrid('reload'); 	// reload the user data
		$('#dlg').dialog('close');
	},'json');
}

function replayContentView() {
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#comment-id').val(row.id);
		$('#replay-username').text(row.username);
		$('#replay-timestamp').text(row.timestamp);
		$('#comment-content').text(row.content);
		$('#dlg').dialog('open');
	} else {
 		$.messager.show({  
			title:'提示',  
			msg:'请选择一条记录',  
			timeout:5000,  
			showType:'slide'  
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
		$.post('/index.php?r=bShopComment/update',{id:ids,status:status},function(result){
			$('#dg').datagrid('reload'); 	// reload the user data
		},'json');
	}			
}
</script>