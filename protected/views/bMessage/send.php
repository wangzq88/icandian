<style type="text/css">
#message-ff table td{ padding:5px 0;}
#message-ff tr td:first-child{text-align:right; padding-right:10px; font-weight:bold;}
#message-ff tr td:first-child a{cursor: help;border-bottom: 1px dotted #999999;text-decoration:none; color:inherit;}
textarea{padding:2px;}
textarea:focus{border:1px solid #09F; padding:2px;}
</style>
<table id="dg" class="easyui-datagrid" style="height:450px" title="消息管理 / 发信箱" 
data-options="url:'/index.php?r=bMessage/send',toolbar:'#toolbar',pagination:true,rownumbers:true,fitColumns:true,singleSelect:true,pageList:[10,15]">  
        <thead>  
            <tr>  
	            <th data-options="field:'id',hidden:true">ID</th>
                <th data-options="field:'receive_uid',hidden:true">接收者uid</th>  
                <th data-options="field:'receive_name'" width="15%">接收者</th>  
                <th data-options="field:'message'" width="50%">内容</th> 
                <th data-options="field:'timestamp'" width="15%">发送时间</th>  
            </tr>  
        </thead>  
    </table>
    <div id="toolbar" style="padding:5px 10px;">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="viewMessage()">查看</a>  &nbsp;&nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" title="给管理员写信" iconCls="icon-add" plain="true" onclick="setdMessageView()">写信</a>  &nbsp;&nbsp;&nbsp;        
        <form style="display:inline-block;float:right;" action="/index.php?r=bMessage/send" id="search-form">
        接收者：<input type="text" name="receive_name" style="width:120px" onkeyup="return enterKeyEventHandler(event,searchMessage);" onkeydown="return enterKeyEventHandler(event,searchMessage);" onkeypress="return enterKeyEventHandler(event,searchMessage);"/>&nbsp;&nbsp;     
                        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="searchMessage()">搜索</a> 
        </form>        
    </div>    
    
<!--查看信息-->
<div id="dlg" class="easyui-dialog" style="width:550px;height:400px;padding:10px 10px 0 10px;" data-options="resizable:false,modal:true,closed:true">
	<form id="ff"> 
    	<table>  
        <tr>  
            <td class="label">接收者：</td>
            <td><input type="text" name="receive_name" readonly="readonly" /></td> 
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

function searchMessage() {
	var result_list = $('#search-form').serializeArray();
	$("#dg").datagrid('load',{
		receive_name: result_list[0].value
	});
}
</script> 
<div style="width:450px;height:350px;padding:10px 20px" id="send-message-view"  class="easyui-dialog" data-options="maximizable:false,resizable:false,modal:true,closed:true,draggable:false,title:'写信'" buttons="#message-dlg-buttons">
<form id="message-ff" method="post" action="/index.php?r=bMessage/create">  
 <table>         
 	<tr>
    	<td style="font-weight:normal;">接收人：</td>
        <td id="apply-shop-name-title">系统管理员</td>
    </tr>
    <tr>
	    <td style="vertical-align:top;font-weight:normal;">内容：</td>
    	<td><textarea name="message" rows="6" cols="35" id="send-message"></textarea></td>
    </tr>
 </table>       
</form>
</div>        
<div id="message-dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="sentMessageAction()">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#send-message-view').dialog('close');">取消</a>
</div> 
<script type="text/javascript"> 
 function setdMessageView() {
 	 $('#send-message-view').dialog('open');
 }
 
 function sentMessageAction() {
	 var message = $.trim($("#send-message").val());
	 if(message == '') return false;
	 $.post($("#message-ff").attr("action"),$("#message-ff").serialize(),function(data) {
		 show_friendly_tip(data.info,$("#message-ff"),1);
		 if(data.success > 0)  {
 			 jQuery("#dg").datagrid("reload");
			 setTimeout("$('#send-message-view').dialog('close')",5000);
		 }
	 });
 }
</script>