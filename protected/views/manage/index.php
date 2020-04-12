<style type="text/css">
.panel-body{  
    background:#f0f0f0;  
}  
.panel-header{  
    background:#fff url('/images/manage/panel_header_bg.gif') no-repeat top right;  
}  
.panel-tool-collapse{  
    background:url('/images/manage/arrow_up.gif') no-repeat 0px -3px;  
}  
.panel-tool-expand{  
    background:url('/images/manage/arrow_down.gif') no-repeat 0px -3px;
}  	
.easyui-panel a{text-decoration:none;color:#000; line-height:1.8em;}
.easyui-panel a:hover{text-decoration:underline;}
</style>
 
    <script type="text/javascript">
	function addTab(url,object){
		var title = $(object).text();
		parent.frame_content.location = url;
	}

</script>    
<div style="width:200px;height:auto;background:#7190E0;padding:5px;">  
    <div class="easyui-panel" title="基本信息" collapsible="true" style="width:200px;height:auto;padding:10px;">  
        <a href="javascript:void(0);" onClick="addTab('/index.php?r=bShop/index',this)">餐店信息</a><br/>  
        <a href="<?php echo $this->createUrl('shop/index',array('id'=>Yii::app()->user->shop_id));?>" target="_blank">查看店面</a>
    </div>  
    <br/>  
    <div class="easyui-panel" title="美食分类" collapsible="true" collapsed="true" style="width:200px;height:auto;padding:10px;">  
     <a href="javascript:void(0);" onClick="addTab('/index.php?r=foodCategories/index',this)">菜单分类管理</a><br/>  
    </div>  
    <br/>  
    <div class="easyui-panel" title="美食管理" collapsible="true" collapsed="true" style="width:200px;height:auto;padding:10px;">  
      <a href="javascript:void(0);" onClick="addTab('/index.php?r=food/index',this)">菜单管理</a> 
        <br/>  
		<a href="javascript:void(0);" onClick="addTab('/index.php?r=package/index',this)">套餐管理</a> 
    </div>  
    <br/>  
    <div class="easyui-panel" title="订单管理" collapsible="true" collapsed="true" style="width:200px;height:auto;padding:10px;">  
      <a href="javascript:void(0);" onClick="addTab('/index.php?r=orderSMS/index',this)">查看订单</a> 
    </div>  
    <br/>        
    <div class="easyui-panel" title="消息管理" collapsible="true" collapsed="true" style="width:200px;height:auto;padding:10px;">  
      <a href="javascript:void(0);" onClick="addTab('/index.php?r=bMessage/receive',this)">收信箱</a> 
        <br/>  
		<a href="javascript:void(0);" onClick="addTab('/index.php?r=bMessage/send',this)">发信箱</a> 
 <br/>  
		<a href="javascript:void(0);" onClick="addTab('/index.php?r=bShopComment/index',this)">餐店留言</a>         
    </div>  
    <br/>      
    <div class="easyui-panel" title="我的信息" collapsible="true" style="width:200px;height:auto;padding:10px;">  
        <p>尊敬的<?php echo Yii::app()->user->name;?>，您好！</p>
        <p>上次登录时间 <?php echo date('Y-m-d H:i:s',Yii::app()->user->last_visit);?></p>
        <p>上次登录IP <?php echo Yii::app()->user->ip;?></p>
        <p><a href="javascript:void(0);" onClick="window.top.location='/index.php?r=site/logout'";>安全退出</a> </p>
    </div>  
</div>          