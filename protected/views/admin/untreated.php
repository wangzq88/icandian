<table id="dg" class="easyui-datagrid" style="height:500px"  url="/index.php?r=admin/untreated"  toolbar="#toolbar" pagination="true"  rownumbers="true" fitColumns="true" singleSelect="true">  
	<thead>  
		<tr>  
			<th field="shop_id" hidden="true">ID</th>
			<th field="shop_name" width="30%">未处理商家名称</th>  
			<th field="shop_description" width="50%">商家介绍</th>  
		</tr>  
	</thead>  
</table>
<div id="toolbar" style="padding:5px 10px;">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editShangjia()">编辑分类</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="delShangjia()">删除分类</a>
<form style="display:inline-block;float:right;" action="/index.php?r=admin/search" id="search-form">
商家名字：<input type="text" name="categories_name" style="width:120px" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);"/>&nbsp;
<a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="searchShangjia()">搜索</a> 
</form>        
</div>     
 <?php echo $this->renderPartial('_form',array(
			'province_list'=>$province_list,
			'cities'=>$city_list,
			'regions'=>$region_list
		)); ?> 