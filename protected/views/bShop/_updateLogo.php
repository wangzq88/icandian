 <!--修改Logo视图-->
<div style="width:700px;height:550px;padding:10px 20px" id="update-logo-view"  class="easyui-dialog" data-options="maximizable:false,resizable:false,modal:true,closed:true,draggable:false" buttons="#dlg-buttons">
	<form id="logo-ff" method="post" action="/index.php?r=bShop/updatePicture" enctype="multipart/form-data" target="frameFile"> 
    <div class="demo-info" style="display:none;">
            <div class="demo-tip icon-tip"></div>
            <div id="logo_tip"></div>
        </div>      
    	<input type="hidden" name="picture_path" value="" id="logo_img_path" />
         <input type="hidden" name="flag" value="logo" />
        <input type="hidden" name="x1" value="" id="logo_x1" />
        <input type="hidden" name="y1" value="" id="logo_y1" />
        <input type="hidden" name="x2" value="" id="logo_x2" />
        <input type="hidden" name="y2" value="" id="logo_y2" />    
    </form>        
   <div id="thumbnails"></div>
   <div id="preview" style="width:<?php echo $preview_width;?>px; height:<?php echo $preview_height;?>px; overflow: hidden;">
   </div>
   <div style="clear:both">
    <table> 
        <tbody>
        <tr> <td><strong>上传 Logo：</strong></td><td> <input type="file" name="logo_img" id="logo_img"/></td></tr>
        </tbody>
    </table>   
    </div>
</div>        
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="submitLogoForm();">保存</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#update-logo-view').dialog('close');">取消</a>
</div>   
<script type="text/javascript"> 
$("#update-logo-view").dialog({  
	title: '更改餐店 Logo',  
	onClose:function() {
		if(ias != undefined)
			ias.cancelSelection();
	},
	onOpen:function() {
		logoHandler($('#shop-logo').attr("src"),<?php echo SHOP_LOGO_VIEW_WIDTH;?>,<?php echo SHOP_LOGO_VIEW_HEIGHT;?>);
	}
}); 
$('#logo_img').uploadify({
	'formData'     : {
		'flag' : 'logo',
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
   //      $("#logo_img").hide();
    },
	'onUploadComplete' : function(file) {
   //      $("#logo_img").show();
    },
 	'onUploadSuccess' : function(file, data, response) {
		var obj = $.parseJSON(data);
		if(obj.success) {
			logoHandler(obj.picture,obj.width,obj.height);
		} else {
			 show_friendly_tip(obj.info,$('#ff'),2); 
		}
     }	
});	

function logoHandler(src,width,height) {
	addNormalImage("newlogo",src,width,height,"thumbnails");
	if(ias != undefined)
		ias.cancelSelection();		
	ias = $('#newlogo').imgAreaSelect({ instance: true,aspectRatio: '1:1', handles: true,x1:0,y1:0,x2:100,y2:100,onSelectChange: preview });	
	addPreviewImage(src,"preview");			
	$("#logo_img_path").val(src);		
}

function preview(img, selection) {
	if (!selection.width || !selection.height)
		return;
	var scaleX = <?php echo $preview_width;?> / selection.width;
	var scaleY = <?php echo $preview_height;?> / selection.height;

	$('#preview img').css({
		width: Math.round(scaleX * img.width),
		height: Math.round(scaleY * img.height),
		marginLeft: -Math.round(scaleX * selection.x1),
		marginTop: -Math.round(scaleY * selection.y1)
	});

	$('#logo_x1').val(selection.x1);
	$('#logo_y1').val(selection.y1);
	$('#logo_x2').val(selection.x2);
	$('#logo_y2').val(selection.y2);
	$('#logo_w').val(selection.width);
	$('#logo_h').val(selection.height);    
}
</script>