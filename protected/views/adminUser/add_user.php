<!--新增用户窗口-->
<div id="dlg" class="easyui-dialog" style="width:500px;height:400px;padding:10px 20px 0 50px"
   data-options="resizable:false,modal:true,closed:true" buttons="#dlg-buttons">
   <div class="demo-info" style="display:none;">
		<div class="demo-tip icon-tip"></div>
		<div id="tip">用户信息已经更新！</div>
	</div>  
<form id="ff" method="post" action="/index.php?r=adminUser/create"> 
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
                    	<input type="email" name="email" class="easyui-validatebox" data-options="required:true,validType:'email'"/>
        			</td>  
                </tr>
                <tr>  
                    <td class="label">修改密码：</td>  
                    <td>
                    	<input type="password" name="password" id="password"/>
                        &nbsp;<span style="color:#C00;display:none;" id="password-tip">*密码输入不一致</span>
        			</td>  
                </tr>
                <tr>  
                    <td class="label">再次输入密码：</td>  
                    <td>
                    	<input type="password" name="confirm" id="confirm"/>
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
                    		<option value="1" selected>普通用户</option>
                        	<option value="2">商家</option>
                        	<option value="3">管理员</option>
                        </select>
                    </td>  
                </tr>  
				<tr>  
                    <td class="label"><a href="javascript:void(0);" title="如果设置为‘否’，用户是无法登录的" class="easyui-tooltip">激活</a>：</td>  
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
                    		<option value="0" selected>否</option>
                        </select>
                    </td>  
                </tr>                 
               </table>
               </form>        
</div>        
<div id="dlg-buttons">
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitForm();">保存</a>
    <a href="javascript:void(0);" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlg').dialog('close');">取消</a>
</div>  
<script type="text/javascript">
function submitForm() {
	if($.trim($("#password").val()) == '') {
		return false;
	}
	if($.trim($("#password").val()) != '' && $("#password").val() != $("#confirm").val()) {
		$("#password-tip").show();
		return false;
	}
	var rsa = new RSAKey();
	rsa.setPublic(public_key, public_length);	
	var initpwd = $("#password").val();
	var res = rsa.encrypt(initpwd);
	if(res) {
		$("#password").val(res);
	}
	$.post($("#ff").attr("action"),$("#ff").serialize(),function(data) {
		$("#tip").text(data.info).parent(".demo-info").show();
		if(data.success != 0) {
			$('#dg').datagrid('reload'); 
		}
		setTimeout('$("#tip").parent(".demo-info").hide();',2000);
	});			
	$("#password").val(initpwd);
}

function addUser() {
	$("#tip").parent(".demo-info").hide();
	document.getElementById("ff").reset();
	$('#dlg').dialog('open').dialog('setTitle','新增用户');
}

$("#password,#confirm").blur(function() {
	var password = $.trim($("#password").val());
	var confirm_password =  $.trim($("#confirm").val());
	if(password != '' && confirm_password != '') {
		if(password != confirm_password) {
			$("#password-tip").show();
		} else {
			$("#password-tip").hide();
		}
	} 
});
</script>