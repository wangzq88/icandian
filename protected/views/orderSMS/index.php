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
<table id="dg" style="height:500px" title="订单管理 / 查看订单" data-options="url:'/index.php?r=orderSMS/index',toolbar:'#toolbar',pagination:true,rownumbers:true,fitColumns:true,singleSelect:false,pageList:[10,15,20]">  
	<thead>  
		<tr>  
        	<th data-options="field:'ck',checkbox:true"></th>
        	<th data-options="field:'send_uid',hidden:true">ID</th>
			<th data-options="field:'send_username',align:'center'" width="10%">用户</th>  
            <th data-options="field:'message',align:'center'" width="30%">订单内容</th>  
            <th data-options="field:'phone',align:'center'" width="10%">联系电话</th>
            <th data-options="field:'shop_id',hidden:true">餐店ID</th>
            <th data-options="field:'receive_uid',hidden:true">接收人</th>
            <th data-options="field:'flag',hidden:true">订单状态</th>
            <th data-options="field:'flag_text',align:'center',formatter:formatFlag" width="10%">订单状态</th>
            <th data-options="field:'timestamp',align:'center'" width="15%">下单时间</th>  
		</tr>  
	</thead>  
</table>
<div id="toolbar" style="padding:10px;height:auto">
<div style="margin-bottom:5px"> 
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" title="标记为处理" onclick="setFlagStatus(2)">标记为处理</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" title="标记为作废" onclick="setFlagStatus(3)">标记为作废</a>  
        <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="deleteOrderSMS()">删除</a>  
        </div>
         <div> 
<form style="display:inline-block;padding-left:5px;" action="/index.php?r=orderSMS/index" id="search-form">
        用户名：<input type="text" name="send_username" style="width:120px" onkeyup="return enterKeyEventHandler(event,searchOrderSMS);" />&nbsp;
        联系电话：<input type="text" name="phone" style="width:120px" onkeyup="return enterKeyEventHandler(event,searchOrderSMS);" />&nbsp;
		下单时间： <input class="easyui-datebox" style="width:100px" name="timestamp[]" />  
      — <input class="easyui-datebox" style="width:100px;"  name="timestamp[]"/>    &nbsp;  
<a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-search" onclick="searchOrderSMS()">搜索</a> 
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
	  '<div class="c-item"><span class="c-label">用户:</span> <span class="c-content">'+rowData.send_username+'</span></div>'+
	  '<div class="c-item"><span class="c-label">订单内容:</span>  <span class="c-content">'+rowData.message+'</span></div>'+
	  '<div class="c-item"><span class="c-label">联系电话:</span> <span class="c-content"> '+rowData.phone+'</span></div>'+
	  '<div class="c-item"><span class="c-label">订单状态:</span> <span class="c-content"> '+rowData.flag_text+'</span></div>'+
	  '<div class="c-item"><span class="c-label">下单时间:</span>  <span class="c-content">'+rowData.timestamp+'</span></div>';
	  html += '</div><div class="clear"></div>';
	  return html;  
  }  
});  

function searchOrderSMS() {
	var result_list = $('#search-form').serializeArray();
	$("#dg").datagrid('load',{
		send_username: result_list[0].value,
		phone: result_list[1].value,
		"add_time[0]":result_list[2].value,
		"add_time[1]":result_list[3].value	
	});	
}
		
function setFlagStatus(flag) {
	var rows = $('#dg').datagrid('getChecked');
	if (rows){
		var ids = new Array();
		for(var i = 0; i < rows.length; i++) {
			ids[i] =  rows[i].id ;
		}
		ids = ids.join(',');
		$.post('/index.php?r=orderSMS/update',{id:ids,flag:flag},function(result){
			$('#dg').datagrid('reload'); 	// reload the user data
		},'json');
	}			
}

function deleteOrderSMS() {
	var rows = $('#dg').datagrid('getChecked');
	if (rows){
		$.messager.confirm('确认','一旦删除将不能恢复，你确定要删除选中的记录吗？',function(r){
			if (r){
				var ids = new Array();
				for(var i = 0; i < rows.length; i++) {
					ids[i] =  rows[i].id;
				}
				ids = ids.join(',');		
				$.post('/index.php?r=orderSMS/delete',{id:ids},function(result){
					$('#dg').datagrid('reload'); 	// reload the user data
				},'json');
			}
		});
	}	
}

function formatFlag(val,row){
	switch(row.flag) {
		case '1':
			return '<span style="color:#468847;">'+val+'</span>';  
			break;
		case '2':
			return '<span style="color:#b94a48;">'+val+'</span>';  
			break;
		case '3':
			return '<span style="color:#c09853;">'+val+'</span>';  		
			break;
		default:
			return val;  
	}
}  

function checkCronAction() 
{
	$.post('/index.php?r=shopBookLog/view',function(result){
		var time = getCookie('back_sp_bk_time');
		if(result.timestamp != time &&  time != null && time != "") {
			$.messager.show({  
				title:'提示',  
				msg:'尊敬的商家，有新的订单需要处理，您可以点击刷新按钮来查看。\n时间：'+result.time,  
				timeout:300000,  
				showType:'slide',
				style:{  
                    right:'',  
                    top:document.body.scrollTop+document.documentElement.scrollTop,  
                    bottom:''  
                }  				  
			}); 		
		}
		setCookie('back_sp_bk_time',result.timestamp);
	},'json');	
}
setInterval('checkCronAction()',60000);
</script>