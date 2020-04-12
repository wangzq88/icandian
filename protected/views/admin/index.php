<style type="text/css">

</style>
 
<script type="text/javascript">
function addTab(url,object){
	var title = $(object).text();
	if ($('#manage_place').tabs('exists',title)){
			$('#manage_place').tabs('select', title);
	} else {		
		$('#manage_place').tabs('add',{
			title:title,
			content:'<iframe scrolling="auto" frameborder="0" width="100%" height="100%" src="'+url+'"></iframe>',
//			iconCls:'icon-save',
			fit:true,
			border:false,
			closable:true/*,
			tools:[{
				iconCls:'icon-mini-refresh',
				handler:function(){
					
				}
			}]*/
		});
	}
}

</script>    
    <div class="easyui-layout" style="width:100%;min-height:700px;">  
       <!-- <div data-options="region:'north'" style="height:50px"></div>  -->
        <!--<div data-options="region:'south',split:true" style="height:50px;"></div>  -->
        <div data-options="region:'west',split:true" style="width:250px;">  
        	<div class="easyui-panel" title="导航" data-options="collapsible:false,border:false" > 
                <ul class="easyui-tree" style="min-height:650px;" data-options="border:false">  
                    <li>  
                        <span>后台管理</span>  
                        <ul>  
                            <li data-options="state:'closed'">  
                                <span>商家管理</span>  
                                <ul>  
                                    <li>  
                                        <a href="javascript:void(0);" onClick="addTab('/index.php?r=admin/already',this)">已加盟商家</a>  
                                    </li>  
                                    <li>  
                                        <a href="javascript:void(0);" onClick="addTab('/index.php?r=adminShopApply/index',this)">未处理商家</a>  
                                    </li>  
                                </ul>  
                            </li> 
                            <li data-options="state:'closed'">
                                <span>评论管理</span>  
                                <ul>     
                            		<li><a href="javascript:void(0);" onClick="addTab('/index.php?r=adminFeedback/index',this)">反馈留言</a></li>
                            		<li><a href="javascript:void(0);" onClick="addTab('/index.php?r=adminShopComment/index',this)">餐店留言</a></li>                                                                          
                                 </ul>                       
                            </li>
                            <li>  
                                <span>地区管理</span>  
                                <ul>  
                                    <li><a href="javascript:void(0);" onClick="addTab('/index.php?r=region/index',this)">区域管理</a></li>  
                                    <li><a href="javascript:void(0);" onClick="addTab('/index.php?r=area/index',this)">地段管理</a></li>  
                                    <li><a href="javascript:void(0);" onClick="addTab('/index.php?r=sections/index',this)">路段管理</a></li>  
                                </ul>  
                            </li>  
                            <li><a href="javascript:void(0);" onClick="addTab('/index.php?r=adminUser/index',this)">用户管理</a></li>                          
                        </ul>  
                    </li>  
                </ul>          
			 </div> 
        </div>  
        
		<div id="manage_place" class="easyui-tabs" data-options="region:'center'">

        </div>  
	</div>