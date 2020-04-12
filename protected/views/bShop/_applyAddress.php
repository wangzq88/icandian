<div style="width:450px;height:350px;padding:10px 20px" id="apply-address-view"  class="easyui-dialog" data-options="maximizable:false,resizable:false,modal:true,closed:true,draggable:false,title:'短消息'" buttons="#address-dlg-buttons">
<form id="address-ff" method="post" action="/index.php?r=bShop/applyInfo">  
 <table>         
 	<tr>
    	<td style="font-weight:normal;">主题：</td>
        <td id="apply-shop-address-title">申请修改餐店地址</td>
    </tr>
    <tr>
	    <td style="vertical-align:top;font-weight:normal;">内容：</td>
    	<td><textarea name="message" rows="6" cols="35" placeholder="填写餐店的新地址" id="apply-shop-address-message"></textarea></td>
    </tr>
 </table>       
</form>
</div>        
<div id="address-dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="applyShopAddressAction()">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#apply-address-view').dialog('close');">取消</a>
</div> 
<script type="text/javascript"> 
 function applyShopAddressView() {
 	 $('#apply-address-view').dialog('open');
 }
 function applyShopaddressAction() {
	 var message = $.trim($("#apply-shop-address-message").val());
	 if(message == '') return false;
	 var title = encodeURI($("#apply-shop-address-title").text());
	 $.post($("#address-ff").attr("action"),$("#address-ff").serialize()+'&title='+title,function(data) {
		 show_friendly_tip(data.info,$("#address-ff"),1);
		 if(data.success > 0)  {
			 setTimeout("$('#apply-address-view').dialog('close')",5000);
		 }
	 });
 }
</script>