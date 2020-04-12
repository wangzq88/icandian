<!--编辑用户窗口-->
<div id="edit-dlg" class="easyui-dialog" style="width:500px;height:400px;padding:10px 20px 0 50px"
   data-options="resizable:false,modal:true,closed:true" buttons="#edit-dlg-buttons">
   <div class="demo-info" style="display:none;">
		<div class="demo-tip icon-tip"></div>
		<div id="edit-tip">用户信息已经更新！</div>
	</div>     
        <div style="text-align:center;display:none;font-weight:bold;" id="edit-tip">用户信息已经更新！</div>
<form id="edit-ff" method="post" action="/index.php?r=adminUser/update"> 
	<input type="hidden" name="uid" />
            <table>  
                <tr>  
                    <td class="label"><a href="javascript:void(0);" title="可以是英文或者中文" class="easyui-tooltip">用户名</a>：</td>  
                    <td>
                    	<input type="text" name="username" />
        			</td>  
                </tr> 
                <tr>  
                    <td class="label"><a href="javascript:void(0);" title="邮箱必须在本站是没有注册过的" class="easyui-tooltip">邮箱</a>：</td>  
                    <td>
                    	<input type="email" name="email" />
        			</td>  
                </tr>
                <tr>  
                    <td class="label">修改密码：</td>  
                    <td>
                    	<input type="password" name="password" id="edit-password"/>
                        &nbsp;<span style="color:#C00;display:none;" id="edit-password-tip">*密码输入不一致</span>
        			</td>  
                </tr>
                <tr>  
                    <td class="label">再次输入密码：</td>  
                    <td>
                    	<input type="password" name="confirm" id="edit-confirm"/>
        			</td>  
                </tr>                                                 
                <tr>  
                    <td class="label"><a href="javascript:void(0);" title="输入用户的手机号码，可以不填" class="easyui-tooltip">手机</a>：</td>  
                    <td><input type="text" name="mobile" placeholder="可以不填"/></td>  
                </tr>  
                <tr>  
                    <td class="label">身份：</td>  
                    <td>
                    	<select name="flag">
                    		<option value="1">普通用户</option>
                        	<option value="2">商家</option>
                        	<option value="3">管理员</option>
                        </select>
                    </td>  
                </tr>  
				<tr>  
                    <td class="label"><a href="javascript:void(0);" title="如果设置为'否'，用户是无法登录的" class="easyui-tooltip">激活</a>：</td>  
                    <td>
                    	<select name="status">
                            <option value="1">是</option>
                    		<option value="0">否</option>
                        </select>
                    </td>  
                </tr>  
				<tr>  
                    <td class="label"><a href="javascript:void(0);" title="邮箱是否通过发送邮件验证过的" class="easyui-tooltip">邮箱验证</a>：</td>  
                    <td>
                    	<select name="valid_email">
                            <option value="1">是</option>
                    		<option value="0">否</option>
                        </select>
                    </td>  
                </tr>                              
                 <tr>  
                    <td class="label">注册时间：</td>  
                    <td><input type="text" name="timestamp" readonly="readonly"/></td>  
                </tr>                                                                                                  
               </table>
               </form>        
</div>        
<div id="edit-dlg-buttons">
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitEditForm();">保存</a>
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#edit-dlg').dialog('close');">取消</a>
</div>  
<script type="text/javascript">
function submitEditForm() {
	if($.trim($("#edit-password").val()) != '' && $("#edit-password").val() != $("#edit-confirm").val()) {
		$("#edit-password-tip").show();
		return false;
	}
	if($.trim($("#edit-password").val()) != '') {
		var rsa = new RSAKey();
		rsa.setPublic(public_key, public_length);	
		var res = rsa.encrypt($("#edit-password").val());
		if(res) {
			$("#edit-password").val(res);
		}
	}
	$.post($("#edit-ff").attr("action"),$("#edit-ff").serialize(),function(data) {
		$("#edit-tip").text(data.info).parent(".demo-info").show();
		if(data.success != 0) {
			$('#dg').datagrid('reload'); 
			setTimeout('$("#edit-tip").parent(".demo-info").hide();$("#edit-dlg").dialog("close");',2000); 
		}
		setTimeout('$("#edit-tip").parent(".demo-info").hide();',2000);
	});			
}

function editUser() {
	$("#edit-tip").parent(".demo-info").hide();
	var row = $('#dg').datagrid('getSelected');
	if (row){	 
		$('#edit-ff').form('load',row);
		$('#edit-password').val('');
		$('#edit-dlg').dialog('open').dialog('setTitle','编辑用户');	
	}
}

$("#edit-password,#edit-confirm").blur(function() {
	var password = $.trim($("#edit-password").val());
	var confirm_password =  $.trim($("#edit-confirm").val());
	if(password != '' && confirm_password != '') {
		if(password != confirm_password) {
			$("#edit-password-tip").show();
		} else {
			$("#edit-password-tip").hide();
		}
	} 
});
</script>