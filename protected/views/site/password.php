<div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li>
                  <a href="/index.php?r=user">个人中心</a> <span class="divider">/</span>
                </li>
                <li class="active">修改密码</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main fr w79p">
              	<div class="hd bgl_c">
                	<h2 class="pl15 g3">修改密码</h2>
                </div>
                <div class="bd p15 bd1">
                    <form class="q_login_form" action="/index.php?r=site/updatePassword" method="post" id="q_regedit_tab">
                        <label><span class="label_des"><b class="r3">&lowast;</b>原密码:</span><input type="password" class="s_input" name="old" id="old"/></label>
                        <label><span class="label_des"><b class="r3">&lowast;</b>新密码:</span><input type="password" class="s_input" name="new" id="new"/></label> 
                        <label><span class="label_des"><b class="r3">&lowast;</b>重复密码:</span><input type="password" class="s_input" name="repeat" id="repeat"/></label>                                        
                        <div class="mt10 pl85"><span class="add_tbn_red"><input type="button" value="确认修改" class="btn_red" onclick="updatePasswordAction(this)" data-loading-text="请稍候..." autocomplete="off"/></span></div>            </form>                 	
                </div>
              </div>
              <!--/中心右边栏-->              
              <?php $this->widget('UserMenu'); ?>
              <!--/中心左边栏-->
            </div>
			
        </div>