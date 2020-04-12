<div style="width:450px;height:350px;padding:10px 20px" id="apply-name-view"  class="easyui-dialog" data-options="maximizable:false,resizable:false,modal:true,closed:true,draggable:false,title:'短消息'" buttons="#name-dlg-buttons">
<form id="name-ff" method="post" action="/index.php?r=bShop/applyInfo">  
 <table>         
 	<tr>
    	<td style="font-weight:normal;">主题：</td>
        <td id="apply-shop-name-title">申请修改餐店名称</td>
    </tr>
    <tr>
	    <td style="vertical-align:top;font-weight:normal;">内容：</td>
    	<td><textarea name="message" rows="6" cols="35" placeholder="填写新餐店的名称" id="apply-shop-name-message"></textarea></td>
    </tr>
 </table>       
</form>
</div>        
<div id="name-dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="applyShopNameAction()">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#apply-name-view').dialog('close');">取消</a>
</div> 
<script type="text/javascript"> 
 function applyShopNameView() {
 	 $('#apply-name-view').dialog('open');
 }
 function applyShopNameAction() {
	 var message = $.trim($("#apply-shop-name-message").val());
	 if(message == '') return false;
	 var title = encodeURI($("#apply-shop-name-title").text());
	 $.post($("#name-ff").attr("action"),$("#name-ff").serialize()+'&title='+title,function(data) {
		 show_friendly_tip(data.info,$("#name-ff"),1);
		 if(data.success > 0)  {
			 setTimeout("$('#apply-name-view').dialog('close')",5000);
		 }
	 });
 }
</script>