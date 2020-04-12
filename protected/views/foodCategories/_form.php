<style type="text/css">
#dlg table td,#edit-dlg table td{ padding:4px 0;}
#dlg tr td:first-child,#edit-dlg tr td:first-child{text-align:right; padding:0 10px 0 20px;}
#dlg tr td:first-child a,#edit-dlg  tr td:first-child a{cursor: help;border-bottom: 1px dotted #999999;text-decoration:none; color:inherit;}
</style>
 <!--新增分类窗口-->
<div id="dlg" class="easyui-dialog" style="width:600px;height:400px;padding:10px 20px"
        buttons="#dlg-buttons" data-options="modal:true,closed:true">
<form id="ff" method="post" action="/index.php?r=foodCategories/create"> 
            <table>  
                <tr>  
                    <td>美食分类：</td>  
                    <td><input class="easyui-validatebox" type="text" name="FoodCategories[categories_name]" data-options="required:true" /></td>  
                </tr>  
                <tr>  
                    <td>分类介绍：</td>  
                    <td><textarea name="FoodCategories[categories_description]" cols="45" rows="6" placeholder="可以不填"></textarea></td>  
                </tr>  
				 <tr>  
                    <td>显示：</td>  
                    <td>
                    	<select name="FoodCategories[status]">
                        	<option value="1" selected="selected">是</option>
                        	<option value="0">否</option>
                        </select>
                     </td>  
                </tr>                      
				 <tr>  
                    <td>排序：</td>  
                    <td><input class="easyui-numberbox" type="text" name="FoodCategories[ordering]" data-options="missingMessage:'可以不填'" value="0" placeholder="数字越大，显示越靠前。可以不填" title="数字越大，显示越靠前。可以不填"/></td>  
                </tr>                  
               </table>
               </form>        
</div>        
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitForm();">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a>
</div>           

 <!--编辑分类窗口-->
<div id="edit-dlg" class="easyui-dialog" style="width:600px;height:400px;padding:10px 20px"
        data-options="modal:true,closed:true" buttons="#edit-dlg-buttons">
<form id="edit-ff" method="post" action="/index.php?r=foodCategories/update"> 
<input type="hidden" name="categories_id" />
            <table>  
                <tr>  
                    <td>美食分类：</td>  
                    <td><input class="easyui-validatebox" type="text" name="categories_name" data-options="required:true" /></td>  
                </tr>  
                <tr>  
                    <td>分类介绍：</td>  
                    <td><textarea name="categories_description" cols="45" rows="6" placeholder="可以不填"></textarea></td>  
                </tr>  
 <tr>  
                    <td>显示：</td>  
                    <td>
                    	<select name="status">
                        	<option value="1">是</option>
                        	<option value="0">否</option>
                        </select>
                     </td>  
                </tr>                      
				 <tr>  
                    <td>排序：</td>  
                    <td><input class="easyui-numberbox" type="text" name="ordering" data-options="missingMessage:'可以不填'"/></td>  
                </tr>                               
               </table>
               </form>        
</div>        
<div id="edit-dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitEditForm();">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#edit-dlg').dialog('close')">取消</a>
</div>    
 <script>  

		
 function newCategories() {
 	$('#dlg').dialog('open').dialog('setTitle','新建美食分类');
//	$('#ff').form('clear');
 }
 
 function editCategories() {
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#edit-dlg').dialog('open').dialog('setTitle','编辑美食分类');
		$('#edit-ff').form('load',row);
	}	 
 }
        function submitForm(){  
			$.post($("#ff").attr("action"),$("#ff").serialize(),function(data) {
				if(data.success == 0)
					$.messager.alert('提示',data.info,'warning');
				else {
					$('#dlg').dialog('close');
					$('#dg').datagrid('reload'); 
				}
			});
		}
		
		function submitEditForm() {
			$.post($("#edit-ff").attr("action"),$("#edit-ff").serialize(),function(data) {
				if(data.success == 0)
					$.messager.alert('提示',data.info,'warning');
				else {
					$('#edit-dlg').dialog('close');
					$('#dg').datagrid('reload'); 
				}
			});			
		}

	function destroyCategories(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('确认','一旦删除将不能恢复，你确定要删除选中的记录吗？',function(r){
					if (r){
						$.post('/index.php?r=foodCategories/delete',{categories_id:row.categories_id},function(result){
							$('#dg').datagrid('reload'); 	// reload the user data
						},'json');
					}
				});
			}
		}		
		
 		function searchCategories() {
			//console.log();
			var result_list = $('#search-form').serializeArray();
			$("#dg").datagrid('load',{
				categories_name: result_list[0].value
			});
		}		
		
		function searchKeyEventHandler(event) {//console.log(event.type);
			var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;//console.log(keyCode);
			if (keyCode == 13) {
				if(event.type == 'keyup') {
					searchCategories();	
				}
				return true;	
			}	
			return false;	
		}
		
    </script>        