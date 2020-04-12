<style type="text/css">
.label{text-align:right;}
.c-label{  
	display:inline-block;  
	width:100px;  
	font-weight:bold;  
}  
</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/datagrid-detailview.js"></script>
<table id="dg" style="height:500px" data-options="toolbar:'#toolbar',pagination:true,rownumbers:true,fitColumns:true,singleSelect:true">  
	<thead>  
		<tr>  
        	<th data-options="field:'id',hidden:true">ID</th>
			<th data-options="field:'shop_name',width:80,align:'center'">餐店名称</th>  
            <th data-options="field:'uid',hidden:true">商家UID</th>  
            <th data-options="field:'xing_ming',width:50,align:'center'">姓名</th>
			<th data-options="field:'shop_description',width:150,align:'center'">餐店介绍</th>
            <th data-options="field:'shop_address',width:150,align:'center'">详细地址</th>
            <th data-options="field:'mobile',width:100,align:'center'">手机号码</th> 
            <th data-options="field:'qq',width:80,align:'center'">QQ</th> 
            <th data-options="field:'phone',width:100,align:'center'">固定电话</th>  
            <th data-options="field:'status_text',width:50,align:'center'">审核</th>
            <th data-options="field:'status',hidden:true">审核</th>
            <th data-options="field:'message',width:150,align:'center'">审核信息</th>  
		</tr>  
	</thead>  
</table>
<div id="toolbar" style="padding:5px 10px;"> 
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="shopApplyCheck(3)">审核通过</a> 
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="shopApplyCheck(2)">审核不通过</a> 
<?php 
	$key_html = ' onkeyup="return enterKeyEventHandler(event,searchShopApply);" onkeydown="return enterKeyEventHandler(event,searchShopApply);" onkeypress="return enterKeyEventHandler(event,searchShopApply);" ';?>
<form style="display:inline-block;float:right;" action="/index.php?r=adminShopApply/index" id="search-form">
餐店名称：<input type="text" name="shop_name" style="width:100px" <?php echo $key_html;?>/>&nbsp;
手机号码：<input type="text" name="mobile" style="width:100px" <?php echo $key_html;?>/>&nbsp;
        审核：<select name="status" <?php echo $key_html;?>>
        <option value="-1">----</option> 
        <option value="1">未审核</option> 
        <option value="2">审核不通过</option> 
        <option value="3">审核通过</option> 
        </select>&nbsp;
<a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="searchShopApply()">搜索</a> 
</form>        
</div> 
<script type="text/javascript">  
$('#dg').datagrid({  
	url:'/index.php?r=adminShopApply/index',
	view:detailview,
	detailFormatter: function(rowIndex, rowData){  		
		var html = '<table><tbody><tr>';
		html += '<div style="float:left;">'+
		'<p><span class="c-label">餐店名称:</span> '+rowData.shop_name+'</p>'+
		'<p><span class="c-label">餐店地址:</span> '+rowData.shop_address+'</p>'+
		'<p><span class="c-label">餐店描述:</span> '+rowData.shop_description	+'</p>'+
		'<p><span class="c-label">姓名:</span> '+rowData.xing_ming+'</p>'+
		'<p><span class="c-label">手机号码:</span> '+rowData.mobile+'</p>'+
		'<p><span class="c-label">QQ:</span> '+rowData.qq+'</p>'+
		'<p><span class="c-label">固定电话:</span> '+rowData.phone+'</p>'+
		'<p><span class="c-label">审核状态:</span> '+rowData.status_text+'</p>'+
		'<p><span class="c-label">审核信息:</span> '+rowData.message+'</p>'+
		'<p><span class="c-label">uid:</span> '+rowData.uid+'</p></div>';
		html += '</tr></body></table>';
		return html;  
	}  
});  

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
		
function searchShopApply() {
	var result_list = $('#search-form').serializeArray();
	$("#dg").datagrid('load',{
		shop_name: result_list[0].value,
		mobile: result_list[1].value,
		status: result_list[2].value
	});
}

function shopApplyCheck(status) {
	var row = $('#dg').datagrid('getSelected');
	if (row){	
		if(row.status == 3) {
			$.messager.alert('提示','审核已经通过，不能再次设置审核状态。您可以到商家管理页面设置商家的状态！','warning'); 
			return false; 		
		}
		var title = status == 2 ? '审核不通过':'审核通过';
 		$.messager.prompt(title, '输入审核信息', function(r){  
			$.post('/index.php?r=adminShopApply/check',{id:row.id,status:status,message:r},function(result){
				if(result.success > 0) {
					$.messager.alert('提示',result.info,'info');				
					$('#dg').datagrid('reload'); 	// reload the user data
				} else {
					$.messager.show({  
						title:'提示',  
						msg:result.info,  
						timeout:5000,  
						style:{  
							right:'',  
							bottom:''  
						},  				
						showType:'fade'  
					});  					
				}
					
			},'json');
		});  		
	}
}
</script>           