<table id="dg" class="easyui-datagrid" style="height:450px" title="消息管理 / 收信箱" 
data-options="url:'/index.php?r=bMessage/receive',toolbar:'#toolbar',pagination:true,rownumbers:true,fitColumns:true,singleSelect:true,pageList:[10,15]">  
        <thead>  
            <tr>  
	            <th data-options="field:'id',hidden:true">ID</th>
                <th data-options="field:'send_uid',hidden:true">发送者uid</th>  
                <th data-options="field:'send_name'" width="15%">发送者</th>  
                <th data-options="field:'message'" width="50%">内容</th> 
                <th data-options="field:'timestamp'" width="15%">发送时间</th>  
                <th data-options="field:'status',hidden:true">状态</th>  
                <th data-options="field:'status_text'" width="10%">状态</th>  
            </tr>  
        </thead>  
    </table>
    <div id="toolbar" style="padding:5px 10px;">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="viewMessage()">查看</a>   
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delMessage()">删除</a>   &nbsp;&nbsp;&nbsp;
        <form style="display:inline-block;float:right;" action="/index.php?r=foodCategories/search" id="search-form">
        发送者：<input type="text" name="send_name" style="width:120px" onkeyup="return enterKeyEventHandler(event,searchMessage);" onkeydown="return enterKeyEventHandler(event,searchMessage);" onkeypress="return enterKeyEventHandler(event,searchMessage);"/>&nbsp;&nbsp; 
        状态：<select name="is_book" onkeyup="return enterKeyEventHandler(event,searchMessage);" onkeydown="return enterKeyEventHandler(event,searchMessage);" onkeypress="return enterKeyEventHandler(event,searchMessage);">
        <option value="-1">----</option> 
        <option value="0">未查看</option> 
        <option value="1">查看</option> 
        </select>&nbsp;&nbsp;         
                        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="searchMessage()">搜索</a> 
        </form>        
    </div>    
    
<!--查看信息-->
<div id="dlg" class="easyui-dialog" style="width:550px;height:400px;padding:10px 10px 0 10px;" data-options="resizable:false,modal:true,closed:true">
	<form id="ff"> 
    	<table>  
        <tr>  
            <td class="label">发送者：</td>
            <td><input type="text" name="send_name" readonly="readonly" /></td> 
        </tr> 
        <tr>  
            <td class="label">发送时间：</td>  
            <td><input type="text" name="timestamp" readonly="readonly"/></td>  
        </tr>                 
        <tr>  
            <td class="label">内容：</td>  
            <td><textarea name="message" cols="50" rows="8" readonly="readonly"></textarea></td>  
        </tr>                                                  
                                                                      
       </table>
    </form>        
</div>             
<script type="text/javascript">
function viewMessage() {
	var row = $('#dg').datagrid('getSelected');
	if (row){	
		if(row.status == 0) {
			$.post('/index.php?r=bMessage/update','id='+row.id,function(data){

			});
		}
		$('#ff').form('load',row);
		$('#dlg').dialog('open').dialog('setTitle','查看详情');			
	} else {
 		$.messager.show({  
			title:'提示',  
			msg:'请选择一条记录',  
			timeout:5000,  
			showType:'slide'  
        });  		
	}
}
function delMessage(){
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$.messager.confirm('确认','一旦删除将不能恢复，你确定要删除选中的记录吗？',function(r){
			if (r){
				$.post('/index.php?r=bMessage/delete',{id:row.id},function(result){
					$('#dg').datagrid('reload'); 	// reload the user data
			//		console.dir(result);
				});
			}
		});
	}
}
function searchMessage() {
	var result_list = $('#search-form').serializeArray();
	$("#dg").datagrid('load',{
		send_name: result_list[0].value,
		status: result_list[1].value
	});
}
</script> 