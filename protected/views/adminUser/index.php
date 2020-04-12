<style type="text/css">
.label{text-align:right;}
#dlg table td,#edit-dlg table td{ padding:4px 0;}
#dlg tr td:first-child,#edit-dlg tr td:first-child{text-align:right; padding:0 10px 0 20px;}
#dlg tr td:first-child a,#edit-dlg  tr td:first-child a{cursor: help;border-bottom: 1px dotted #999999;text-decoration:none; color:inherit;}
.datagrid-row-detail{ padding:15px 0;}
.c-item{ margin-bottom:10px;}
.c-label{display:inline-block;width:100px;font-weight:bold;text-align:left;}  
.c-content {display:inline-block;}
</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/datagrid-detailview.js"></script> 
<table id="dg" style="height:500px"  url="/index.php?r=adminUser/index"  toolbar="#toolbar" pagination="true"  rownumbers="true" fitColumns="true" singleSelect="true">  
	<thead>  
		<tr>  
        	<th data-options="field:'uid',align:'center'" width="5%">ID</th>
			<th data-options="field:'username',align:'center'" width="15%">用户名</th>  
            <th data-options="field:'email',align:'center'" width="20%">邮箱</th>  
            <th data-options="field:'mobile',align:'center'" width="10%">手机</th>
            <th data-options="field:'flag',hidden:true">标识</th>
            <th data-options="field:'flag_text',align:'center'" width="10%">身份</th>
            <th data-options="field:'valid_email',hidden:true">邮箱验证</th>
            <th data-options="field:'valid_email_text',align:'center'" width="10%">邮箱验证</th>
            <th data-options="field:'status',hidden:true">激活</th>
            <th data-options="field:'status_text',align:'center'" width="5%">激活</th>
            <th data-options="field:'timestamp',align:'center'" width="15%">注册时间</th>  
		</tr>  
	</thead>  
</table>
<div id="toolbar" style="padding:10px;height:auto">
<div style="margin-bottom:5px"> 
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addUser()">新建用户</a> 
        <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">编辑用户</a>  
        </div>
         <div> 
<form style="display:inline-block;padding-left:5px;" action="/index.php?r=adminUser/already" id="search-form">
        用户名：<input type="text" name="username" style="width:120px" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);"/>&nbsp;
        邮箱：<input type="email" name="email" style="width:120px" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);"/>&nbsp;
        手机：<input type="text" name="mobile" style="width:120px" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);"/>&nbsp;        
 		身份：<select name="flag" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        	<option value="0">——</option>
        	<option value="1">普通用户</option>
        	<option value="2">商家</option>
            <option value="3">管理员</option>
        </select>&nbsp;      
        激活：<select name="status" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);">
        	<option value="-1">——</option>
            <option value="1">是</option>
        	<option value="0">否</option>
        </select>&nbsp;  
<a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-search" onclick="searchUser()">搜索</a> 
</form>        
</div>
<div class="clear"></div>
</div>
<?php include 'add_user.php';?>
<?php include 'edit_user.php';?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/rsa/all.js"></script>
<script type="text/javascript">
$('#dg').datagrid({  
  view:detailview,
  detailFormatter: function(rowIndex, rowData){  		
	  var html = '';
  	  html += '<div style="float:left;width: 100%;">'+
	  '<div class="c-item"><span class="c-label">用户ID:</span> <span class="c-content">'+rowData.uid+'</span></div>'+	  
	  '<div class="c-item"><span class="c-label">用户名:</span> <span class="c-content">'+rowData.username+'</span></div>'+
	  '<div class="c-item"><span class="c-label">邮箱:</span>  <span class="c-content">'+rowData.email+'</span></div>'+
	  '<div class="c-item"><span class="c-label">手机:</span> <span class="c-content"> '+rowData.mobile+'</span></div>'+
	  '<div class="c-item"><span class="c-label">身份:</span> <span class="c-content"> '+rowData.flag_text+'</span></div>'+
	  '<div class="c-item"><span class="c-label">邮箱验证:</span> <span class="c-content"> '+rowData.valid_email_text+'</span></div>'+
	  '<div class="c-item"><span class="c-label">激活:</span> <span class="c-content"> '+rowData.status_text+'<span></div>'+
	  '<div class="c-item"><span class="c-label">注册时间:</span>  <span class="c-content">'+rowData.timestamp+'</span></div>';
	  html += '</div><div class="clear"></div>';
	  return html;  
  }  
});  

var public_key="00b0c2732193eebde5b2e278736a22977a5ee1bb99bea18c0681ad97484b4c7f681e963348eb80667b954534293b0a6cbe2f9651fc98c9ee833f343e719c97c670ead8bec704282f94d9873e083cfd41554f356f00aea38d2b07551733541b64790c2c8f400486fd662a3e95fd5edd2acf4d59ca97fad65cc59b8d10cbc5430c53";
var public_length="10001";

function searchUser() {
	var result_list = $('#search-form').serializeArray();
	$("#dg").datagrid('load',{
		username: result_list[0].value,
		email: result_list[1].value,
		mobile:result_list[2].value,
		flag:result_list[3].value,
		status:result_list[4].value
	});	
}

function searchKeyEventHandler(event) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (keyCode == 13) {
		if(event.type == 'keyup') {
			searchUser();	
		}
		return false
	}	
	return true;	
}			
</script>