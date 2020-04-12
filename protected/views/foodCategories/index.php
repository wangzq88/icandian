<table id="dg" class="easyui-datagrid" style="height:450px" title="美食分类 / 菜单分类管理"
            url="/index.php?r=foodCategories/index"  
            toolbar="#toolbar" pagination="true"  
            rownumbers="true" fitColumns="true" singleSelect="true">  
        <thead>  
            <tr>  
	            <th data-options="field:'categories_id',hidden:true">ID</th>
                <th data-options="field:'categories_name'" width="20%">美食分类</th>  
                <th data-options="field:'categories_description'" width="40%">美食分类介绍</th> 
                <th data-options="field:'status',hidden:true">显示</th>  
                <th data-options="field:'status_text'" width="10%">显示</th>  
                <th data-options="field:'ordering'" width="10%">排序</th>
            </tr>  
        </thead>  
    </table>
    <div id="toolbar" style="padding:5px 10px;">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newCategories()">新建分类</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editCategories()">编辑分类</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyCategories()">删除分类</a>   &nbsp;&nbsp;&nbsp;
        <form style="display:inline-block;float:right;" action="/index.php?r=foodCategories/search" id="search-form">
        美食分类：<input type="text" name="categories_name" style="width:120px" onkeyup="return searchKeyEventHandler(event);" onkeydown="return searchKeyEventHandler(event);" onkeypress="return searchKeyEventHandler(event);"/>&nbsp;
                        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="searchCategories()">搜索</a> 
        </form>        
    </div>     
    <?php //echo $this->renderPartial('_form', array('model'=>$model)); ?>
	 <?php echo $this->renderPartial('_form'); ?>