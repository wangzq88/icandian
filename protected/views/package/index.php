<style type="text/css">
#dlg table td,#edit-dlg table td{ padding:4px 0;}
#dlg tr.identity td:first-child,#edit-dlg tr.identity td:first-child{text-align:left; font-weight:bold;}
#dlg tr.identity td:first-child a,#edit-dlg  tr.identity td:first-child a{cursor: help;border-bottom: 1px dotted #999999;text-decoration:none; color:inherit;}
tr.package-item-explain td{ text-align:left;}
</style>
<?php $timestamp = time();?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-easyui/datagrid-detailview.js"></script> 
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/uploadify/uploadify.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/uploadify/jquery.uploadify.min.js"></script>  
<table id="dg" style="height:500px;" title="美食管理 / 套餐管理" data-options="url:'/index.php?r=package/index',pagination:true,rownumbers:true,fitColumns:true,singleSelect:true,pageList:[10,15]" toolbar="#toolbar">  
        <thead>  
            <tr>  
	            <th field="package_id" hidden="true">ID</th>
                <th field="food_ids" hidden="true">food_ids</th>
                <th field="package_name" width="30%">套餐名称</th>  
                <th field="package_price" width="50%">套餐价格</th>  
                <th field="package_remark" width="50%">套餐说明</th>  
                <th field="categories_id" hidden="true">套餐分类ID</th>
                <th field="categories_name">套餐分类</th>  
            </tr>  
        </thead>  
    </table>
    <div id="toolbar">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newPackage()">加拼套餐</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editPackage()">编辑套餐</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyPackage()">删除套餐</a>  
    </div>     
    
 <!--新增分类窗口-->
<div id="dlg" class="easyui-dialog" style="width:600px;height:400px;padding:10px 20px"
       buttons="#dlg-buttons" data-options="maximizable:true,resizable:true,modal:true,closed:true,maximized:true">
<form id="ff" method="post" action="/index.php?r=package/create" target="frameFile"> 
            <table>  
                 <tr>  
                    <td colspan="2"><div class="demo-info" id="easy_ui_friendly_tip"><div class="demo-tip icon-tip"></div><div>在加拼套餐之前，请确认你的菜单是有美食的，否则的话，请先前往<a href="/index.php?r=food/index">菜单管理</a>添加美食。如：在&lt;选择美食一&gt;选择一个“主食”分类，然后在&lt;选择美食二&gt;选择一个“炖品”分类，就会自动帮你生成套餐列表。你可以保存之后，再对每个套餐进行详细的编辑</div></div></td>  
                </tr>             
                <tr class="identity">  
                    <td><a href="javascript:void(0);" title="请选择一个美食分类或者美食" class="easyui-tooltip">选择美食一</a>：</td>  
                    <td>                    
                        <input class="easyui-combotree" url="/index.php?r=package/tree" style="width:250px;" multiple  name="package_id_1" id="package_id_1"/>
                     </td>  
                </tr> 
                <tr class="identity">                  
                    <td><a href="javascript:void(0);" title="请选择另外一个美食分类或者美食" class="easyui-tooltip">选择美食二</a>：</td>  
                    <td>                    
                       <input class="easyui-combotree"  url="/index.php?r=package/tree" style="width:250px;"  name="package_id_2"  id="package_id_2" multiple />&nbsp;&nbsp;<div style="display:inline-block;" id="add-more"><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="ffMoreFood(3)"></a>   
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="ffLessFood(3)"></a></div>
                     </td>  
                </tr>                                              
                <tr class="identity">  
                    <td>套餐分类：</td>  
                    <td>
                    	<select name="categories_id">
                        	<?php foreach($categories as $cat): ?>
                        	<option value="<?php echo $cat['categories_id']; ?>"><?php echo $cat['categories_name']; ?></option> 
                            <?php endforeach; ?>
                    	</select>
                    </td>  
                </tr>                      
<!--                <tr>  
                    <td>套餐说明：</td>  
                    <td>                    
                        <textarea name="package_remark"></textarea>
                     </td>  
                </tr> -->   
                 <tr>  
                    <td colspan="2" id="create_tip" style="display:none">菜式已经创建成功！</td>  
                </tr>                                                                             
               </table>
               </form>        
</div>        
<div id="dlg-buttons">
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitForm();">保存</a>
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">取消</a>
</div>           
<iframe id="frameFile" name="frameFile" style="display:none"></iframe>
 <!--编辑分类窗口-->
<div id="edit-dlg" class="easyui-dialog" style="width:600px;height:400px;padding:10px 20px"
    buttons="#edit-dlg-buttons" data-options="maximizable:true,resizable:true,modal:true,closed:true">
<form id="edit-ff" method="post" enctype="multipart/form-data"  action="/index.php?r=package/update" target="frameFile"> 
<input type="hidden" name="package_id" />
<input type="hidden" name="package_img_path" value="" id="edit_package_img_path"/>
       <table>  
                <tr class="identity">  
                    <td><a href="javascript:void(0);" title="请选择一个美食" class="easyui-tooltip">选择美食一</a>：</td>  
                    <td>                    
                        <input class="easyui-combotree" url="/index.php?r=package/tree" style="width:250px;"   name="food_id[]" id="food_id_1"/>
                     </td>  
                </tr> 
                <tr class="identity">                  
                    <td><a href="javascript:void(0);" title="请选择另外一个美食" class="easyui-tooltip">选择美食二</a>：</td>  
                    <td>                    
                       <input class="easyui-combotree"  url="/index.php?r=package/tree" style="width:250px;"  name="food_id[]"  id="food_id_2" />&nbsp;&nbsp;<div style="display:inline-block;" id="edit-mode"><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="moreFood(3)"></a>   
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="lessFood(3)"></a></div>
                     </td>  
                </tr>  
<!--                <tr>                  
                    <td>编号：</td>  
                    <td>                    
                       <input class="easyui-validatebox"  type="text" name="alias" data-options="required:false,validType:'alpha'" style="width:200px;"  />
                     </td>  
                </tr>  -->              
                <tr class="identity">                  
                    <td>优惠价：</td>  
                    <td>                    
                       <input class="easyui-numberbox"  type="text" name="package_price" data-options="required:true" style="width:200px;"  />
                     </td>  
                </tr>                                                             
                <tr class="identity">  
                    <td>套餐分类：</td>  
                    <td>
                    	<select name="categories_id">
                        	<?php foreach($categories as $cat): ?>
                        	<option value="<?php echo $cat['categories_id']; ?>"><?php echo $cat['categories_name']; ?></option> 
                            <?php endforeach; ?>
                    	</select>
                    </td>  
                </tr>                      
                <tr class="identity">  
                    <td>套餐说明：</td>  
                    <td>                    
                        <textarea name="package_remark" rows="3" cols="40" placeholder="可以不填"></textarea>
                     </td>  
                </tr>    
                 <tr class="identity">  
                    <td><a href="javascript:void(0);" title="该美食的图片，可以不用上传" class="easyui-tooltip">图片</a>：</td>  
                    <td><input type="file" name="package_img"  id="edit_package_img"/></td>  
                </tr>        
                <td colspan="2" id="edit_tip" style="display:none">菜式已经创建成功！</td>                                              
               </table>
               </form>        
</div>        
<div id="edit-dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitEditForm();">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#edit-dlg').dialog('close')">取消</a>
</div>    
<style type="text/css">  
.datagrid-row-detail{ padding:15px 0;}
.c-item{ margin-bottom:10px;}
.c-label{display:inline-block;width:100px;font-weight:bold;text-align:left;}  
.c-content {display:inline-block;}
.clear{ clear:both; margin:0; padding:0; height:0; width:0;}
</style>  
 <script type="text/javascript"> 
$('body').data('ff_package_count',2);//增加套餐窗口，默认是2个套餐可供选择，每点击"+"，数目会加 1
 
//加拼后生成的列表
function checkHandle(node) {
	
		var m = -1,j = 0, i = 0,package_text = '',tmp_list = new Array();
		var package_text_list = new Array();//套餐文本列表
		
		var length = $('body').data('ff_package_count');
		for(i = 0; i < length; i++) {
			m = i + 1;
			package_text = $('#package_id_'+m).combotree('getText');
			if($.trim(package_text) != '') {
				tmp_list = new Array();
				package_text = package_text.split(',');
				for(j = 0; j < package_text.length; j++) {
					if(package_text[j].indexOf('￥') != -1) {
						 tmp_list.push(package_text[j]);
					}
				}
				package_text_list[i] = tmp_list;
			}
		}

		var all_list = new Array();
		var all_price_list = new Array();
		var first_package = package_text_list.shift();
		var package_list = new Array();
		package_list.push(first_package.shift());
		var count = package_text_list.length;
		if(count >= 1) {
			var items,item0;
			do {
				for(i = 0; i < count; i++) {//循环第一维数组
					tmp_list = new Array();
					while(items = package_list.shift()) {
						for(item0 in package_text_list[i]) {
							tmp_list.push(items+','+package_text_list[i][item0]);
						}
					}
					package_list = tmp_list;
				}
				all_list =  all_list.concat(package_list);
				package_list = new Array();
			} while(package_list[0] = first_package.shift());	
			
			$('#ff .package-item-explain').remove();//如果存在历史记录，清空历史记录
			var package,price = 0;
			for(i = 0; i < all_list.length; i++) {
				package = all_list[i].split(',');//
				price = 0;
				text = '';
				for(j = 0; j < package.length; j++) {
					start = package[j].lastIndexOf('￥')+1;//套餐一的每一个价格
					price += parseFloat(package[j].substring(start,package[j].length-1));
				}	
				$('#ff table').append('<tr class="package-item-explain"><td colspan="2">'+package.join('+')+'     特惠价￥<input type="text" value="'+price+'" name="package_price[]" /></td></tr>');
			}
		}
	
}

$('#package_id_1').combotree({
	onCheck: function(node){
		checkHandle(node);
	}
});
 $('#package_id_2').combotree({
	onCheck: function(node){
		checkHandle(node);
	}
});
 
 function newPackage() {
	 document.getElementById("create_tip").style.display="none";
	 document.getElementById("ff").reset();
	 $('#package_id_1').combotree('setValues', []);
	 $('#package_id_2').combotree('setValues', []);
	$('#ff .package-item-explain').remove();
 	$('#dlg').dialog('open').dialog('setTitle','加拼套餐');
	//$('#ff').form('clear');
 }
 
 function editPackage() {
	 document.getElementById("edit_tip").style.display="none";
	var row = $('#dg').datagrid('getSelected');
	if (row){
		var list = row.food_ids.split(',');
		var j = 0;
		for(var i = 0; i < list.length; i++) {
			j = i + 1;
			if(i >= 2) {
				moreFood(j);//生成 combotree，并赋值
			}
			$('#food_id_'+j).combotree('setValue', list[i]);
		}
		$('#edit-dlg').dialog('open').dialog('setTitle','编辑套餐');
		$('#edit-ff').form('load',row);
		//$(".idingcan_flag").trigger('click');
	} else {
		 $.messager.show({  
                title:'提示',  
                msg:'请选中要编辑的美食',  
				timeout:5000,
                showType:'fade',  
                style:{  
                    right:'',  
                    bottom:''  
                }  
           });  
	}	 
 }
        function submitForm(){  
			var m = -1,package_val = '',j = 0,tmp = new Array();
			var length = $('body').data('ff_package_count');
			for(var i = 0; i < length; i++) {
				m = i + 1;
				package_val = $('#package_id_'+m).combotree('getValues');
				if(package_val.length > 1) {
					tmp = new Array();
					for(j in package_val) {
						if(package_val[j].charAt(0) == 'c') continue;
							tmp.push(package_val[j]);
					}
					tmp = tmp.join(',');
					$('#ff').append('<input type="hidden" name="package_id[]" value="'+tmp+'" />');
				}
			}
			
			$('#ff').submit();
			$('#ff input').filter('[name="package_id[]"]').remove();
		}
		
		function submitEditForm() {
			$('#edit-ff').submit();
//			$.post($("#edit-ff").attr("action"),$("#edit-ff").serialize(),function(data) {
//				if(data.success == 0)
//					$.messager.alert('提示',data.info,'warning');
//				else {
//					$('#edit-dlg').dialog('close');
//					$('#dg').datagrid('reload'); 
//				}
//			});			
		}

	function destroyPackage(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('确认','一旦删除将不能恢复，你确定要删除选中的记录吗？',function(r){
					if (r){
						$.post('/index.php?r=package/delete',{package_id:row.package_id},function(result){
							$('#dg').datagrid('reload'); 	// reload the user data
						},'json');
					}
				});
			}
		}		
/**
 * 编辑套餐窗口，点击"+"的处理事件
 */				
function moreFood(i) {
	if(i >= 10) return false;
	var list = ['一','二','三','四','五','六','七','八','九','十'];//最多只能增加到10个菜式
	var text = '选择餐式'+list[i-1];
	j = i + 1;
	var parents = $('#edit-mode').parents('tr');
	$('#edit-mode').remove();
	parents.after('<tr> <td>'+text+'：</td> <td> <input class="easyui-combotree" url="/index.php?r=package/tree" style="width:200px;" name="food_id[]" id="food_id_'+i+'" />&nbsp;&nbsp;<div style="display:inline-block;" id="edit-mode"><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" id="more-btn-'+i+'" onclick="moreFood('+j+')"></a> <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" id="less-btn-'+i+'" onclick="lessFood('+j+')"></a></div> </td> </tr>');	
	$('#food_id_'+i).combotree({   
		required: true  
	});  	 
	$('#more-btn-'+i).linkbutton({   
	});
	$('#less-btn-'+i).linkbutton({   
	});  		
}
/**
 * 编辑套餐窗口，点击"-"的处理事件
 */		
function lessFood(i) {
	if(i <= 3) return false;
	var list = ['一','二','三','四','五','六','七','八','九','十'];
	var text = '选择餐式'+list[i-1];
	j = i - 1;
	var sibling = $('#edit-mode').parents('tr').prev();
	$('#edit-mode').parents('tr').remove();
	sibling.find('td:last').append('&nbsp;&nbsp;<div style="display:inline-block;" id="edit-mode"><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" id="more-btn-'+i+'" onclick="moreFood('+j+')"></a> <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" id="less-btn-'+i+'" onclick="lessFood('+j+')"></a></div>');	
	$('#food_id_'+i).combotree({   
		required: true  
	});  	 
	$('#more-btn-'+i).linkbutton({   
	});
	$('#less-btn-'+i).linkbutton({   
	});  		
}

/**
 * 增加套餐窗口，点击"+"的处理事件
 */	
function ffMoreFood(i) {
	if(i >= 10) return false;
	$('body').data('ff_package_count',i);//保存"选择套餐"数目
	var list = ['一','二','三','四','五','六','七','八','九','十'];//最多只能增加到10个菜式
	var text = '选择餐式'+list[i-1];
	j = i + 1;
	var parents = $('#add-more').parents('tr');
	$('#add-more').remove();
	parents.after('<tr> <td>'+text+'：</td> <td> <input class="easyui-combotree" url="/index.php?r=package/tree" style="width:200px;" name="package_id_'+i+'" id="package_id_'+i+'" multiple />&nbsp;&nbsp;<div style="display:inline-block;" id="add-more"><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" id="ff-more-btn-'+i+'" onclick="ffMoreFood('+j+')"></a> <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" id="ff-less-btn-'+i+'" onclick="ffLessFood('+j+')"></a></div> </td> </tr>');	
	$('#package_id_'+i).combotree({   
		required: true,
		onCheck: function(node){
			checkHandle(node);
		}		  
	});  	 
	$('#ff-more-btn-'+i).linkbutton({   
	});
	$('#ff-less-btn-'+i).linkbutton({   
	});  			
}
/**
 * 增加套餐窗口，点击"-"的处理事件
 */	
function ffLessFood(i) {
	if(i <= 3) return false;
	$('body').data('ff_package_count',i-2);//保存"选择套餐"数目
	var list = ['一','二','三','四','五','六','七','八','九','十'];
	var text = '选择餐式'+list[i-1];
	j = i - 1;
	var sibling = $('#add-more').parents('tr').prev();
	$('#add-more').parents('tr').remove();
	sibling.find('td:last').append('<div style="display:inline-block;" id="add-more"><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" id="ff-more-btn-'+i+'" onclick="ffMoreFood('+j+')"></a> <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" id="ff-less-btn-'+i+'" onclick="ffLessFood('+j+')"></a></div>');	
	$('#ff-more-btn-'+i).linkbutton({   
	});
	$('#ff-less-btn-'+i).linkbutton({   
	});  		
	j--;
	var t = $('#package_id_'+j).combotree('tree');  	 
	var n_check = t.tree('getChecked');	
	$.each(n_check,function(i,n) {
		t.tree('check',n.target);
	});		
}
/**
 * 增加套餐窗口，点击"+"的处理事件
 */			
$.extend($.fn.validatebox.defaults.rules, {  
    alpha: {  
        validator: function(value, param){  
            return !$.trim(value) || value.match(/^[A-Za-z0-9]+$/g);  
        },  
        message: '只能由字母和数字组成.'  
    }  
});  	
$('#dg').datagrid({  
  view:detailview,
  detailFormatter: function(rowIndex, rowData){  		
	  var html = '';
	  var picture = $.trim(rowData.package_img) != '' ? rowData.package_img:'<?php echo FOOD_DEFAULT_LOGO;?>';
	  html += '<img src="'+picture+'" style="width:150px;float:left;margin-top:15px;">';
	  html += '<div style="float:left;margin-left:20px;">'+
	  '<div class="c-item"><span class="c-label">套餐名称:</span>  <span class="c-content">'+rowData.package_name+'</span></div>'+
	  '<div class="c-item"><span class="c-label">价格:</span>  <span class="c-content">￥'+rowData.package_price+'</span></div>'+
	  '<div class="c-item"><span class="c-label">套餐说明:</span>  <span class="c-content">'+rowData.package_remark+'</span></div>'+
	  '<div class="c-item"><span class="c-label">套餐分类:</span>  <span class="c-content">'+rowData.categories_name+'</span></div></div>';
	  html += '<div class="clear"></div>';
	  return html;  
  }  
});  
$('#edit_package_img').uploadify({
	'formData'     : {
		'timestamp' : '<?php echo $timestamp;?>',
		'token'     : '<?php echo md5('unique_salt' . $timestamp);?>',
		'shop_id' : '<?php echo Yii::app()->user->shop_id;?>'
	},
	'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg',
	'fileSizeLimit' : '1MB',
	'buttonText':'选择图片',
	'swf'      : '/assets/uploadify/uploadify.swf',
	'uploader' : '/api/foodUploadify.php',
 	'onUploadSuccess' : function(file, data, response) {
		var obj = $.parseJSON(data);
		if(obj.success) {
			$("#edit_package_img_path").val(obj.food_img);
			$("#edit_package_pic").remove();
			$("#edit_package_img").before('<img src="'+obj.food_img+'" width="100" height="100" id="edit_package_pic"/>');
		} else {
			 show_friendly_tip(obj.info,$('#edit-ff'),2); 
		}
     }	
});		
    </script>           