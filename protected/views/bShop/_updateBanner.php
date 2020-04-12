 <div style="padding:10px 20px" id="update-ad-view"  class="easyui-dialog" data-options="maximizable:false,resizable:false,modal:true,closed:true,draggable:false" buttons="#ad-dlg-buttons">
	<form id="banner-ff" method="post" action="/index.php?r=bShop/updatePicture" enctype="multipart/form-data" target="frameFile"> 
    <div class="demo-info" style="display:none;">
            <div class="demo-tip icon-tip"></div>
            <div id="banner_tip"></div>
        </div>      
    	<input type="hidden" name="picture_path" value="" id="banner_img_path" />
        <input type="hidden" name="flag" value="banner" />
        <input type="hidden" name="x1" value="" id="banner_x1" />
        <input type="hidden" name="y1" value="" id="banner_y1" />
        <input type="hidden" name="x2" value="" id="banner_x2" />
        <input type="hidden" name="y2" value="" id="banner_y2" />    
    </form>        
   <div id="banner-thumbnails"></div>
   <br />
   <div id="banner-preview" style="width:<?php echo $ban_pv_w;?>px; height:<?php echo $ban_pv_h;?>px; overflow: hidden;">
   </div>
   <div style="clear:both">
    <table> 
        <tbody>
        <tr> <td><strong>上传广告牌：</strong></td><td> <input type="file" name="banner_img" id="banner_img"/></td></tr>
        </tbody>
    </table>   
    </div> 
 </div>        
<div id="ad-dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="$('#banner-ff').submit();">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#update-ad-view').dialog('close');">取消</a>
</div> 
<script type="text/javascript"> 
$("#update-ad-view").dialog({  
	title: '更改餐店广告牌',  
	width:$(window).width(),  
    height:$(window).height(), 	
	onClose:function() {
		if(ias != undefined)
			ias.cancelSelection();
	},
	onOpen:function() {
		bannerHandler($('#shop-banner').attr("src"),<?php echo SHOP_BANNER_VIEW_WIDTH;?>,<?php echo SHOP_BANNER_VIEW_HEIGHT;?>);
	}
});  
$('#banner_img').uploadify({
	'formData'     : {
		'flag' : 'banner',
		'timestamp' : '<?php echo $timestamp;?>',
		'token'     : '<?php echo md5('unique_picture' . $timestamp);?>',
		'shop_id' : '<?php echo Yii::app()->user->shop_id;?>'
	},
	'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg',
	'fileSizeLimit' : '1MB',
	'buttonText':'选择图片',
	'swf'      : '/assets/uploadify/uploadify.swf',
	'uploader' : '/api/shopPictureUploadify.php',
	'onUploadStart' : function(file) {
    //     $("#banner_img").hide();
    },
	'onUploadComplete' : function(file) {
    //     $("#banner_img").show();
    },
 	'onUploadSuccess' : function(file, data, response) {
		var obj = $.parseJSON(data);console.log(obj);
		if(obj.success) {
			bannerHandler(obj.picture,obj.width,obj.height);
		} else {
			show_friendly_tip(obj.info,$('#banner-ff'),2); 
		}
     }	
});
function bannerHandler(src,width,height) {
	addNormalImage("newbanner",src,width,height,"banner-thumbnails");
	if(ias != undefined)
		ias.cancelSelection();	
	ias = $('#newbanner').imgAreaSelect({ instance: true,handles: true,x1:0,y1:0,x2:100,y2:98,onSelectChange: bannerPreview });	
	addPreviewImage(src,"banner-preview");					
	$("#banner_img_path").val(src);	
}

function bannerPreview(img, selection) {
	if (!selection.width || !selection.height)
		return;
	var scaleX = <?php echo $ban_pv_w;?> / selection.width;
	var scaleY = <?php echo $ban_pv_h;?> / selection.height;

	$('#banner-preview img').css({
		width: Math.round(scaleX * img.width),
		height: Math.round(scaleY * img.height),
		marginLeft: -Math.round(scaleX * selection.x1),
		marginTop: -Math.round(scaleY * selection.y1)
	});

	$('#banner_x1').val(selection.x1);
	$('#banner_y1').val(selection.y1);
	$('#banner_x2').val(selection.x2);
	$('#banner_y2').val(selection.y2);
}	
</script>