<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
require $_SERVER ['DOCUMENT_ROOT'] . '/protected/config/common.php';
require $_SERVER ['DOCUMENT_ROOT'] . '/protected/config/business.php';
require $_SERVER ['DOCUMENT_ROOT'] . '/api/commomFunction.php';

$verifyToken = md5 ( 'unique_picture' . $_POST ['timestamp'] );

if (! empty ( $_FILES ) && $_POST ['token'] == $verifyToken) {
	switch ($_POST['flag']) {
		case 'logo':
			$save_width = SHOP_LOGO_VIEW_WIDTH;
			$save_height = SHOP_LOGO_VIEW_HEIGHT;
			break;
		case 'banner':
			$save_width = SHOP_BANNER_VIEW_WIDTH;
			$save_height = SHOP_BANNER_VIEW_HEIGHT;
			break;
		default:
			exit ( '{"success":false,"info":"非法的标识"}' );			
	}
	// Validate the file type
	$fileTypes = array ('jpg', 'jpeg', 'gif', 'png' ); // File extensions
	$fileParts = pathinfo ( $_FILES ['Filedata'] ['name'] );
	if (!in_array ( strtolower($fileParts ['extension']), $fileTypes )) {
		exit ( '{"success":false,"info":"不合法的图片格式"}' );
	}
	if ($_FILES ['Filedata'] ['error'] == UPLOAD_ERR_OK && is_uploaded_file ( $_FILES ['Filedata'] ['tmp_name'] )) {
		$upload_dir = TMP_PATH.DS.(int)$_POST['shop_id'].DS;
		if(!is_dir($upload_dir)) {
			mkdir($upload_dir,0777,true);
		}			
		$upload_url = '/tmp/' . ( int ) $_POST['shop_id'] . '/';
		if (! is_dir ( $upload_dir )) {
			mkdir ( $upload_dir, 0777, true );
		}
		$uploadfile = time () . strrchr ( $_FILES ['Filedata'] ['name'], '.' );
		$uploadfile = $upload_dir . $uploadfile;
		if (move_uploaded_file ( $_FILES ['Filedata'] ['tmp_name'], $uploadfile )) {
			list ( $_ori_width, $_ori_height ) = @getimagesize ( $uploadfile );
//			if ($_ori_width < $save_width || $_ori_height < $save_height) {
//				exit ( '{"success":false,"info":"图片的尺寸不合格，请上传像素大于'.$save_width.'*'.$save_height.'的图片"}' );
//			}
			$thumbnailfile = fixedThumbnail($uploadfile,0,0,$_ori_width,$_ori_height,'',$save_width,$save_height);
			if(is_file($thumbnailfile)) {
				$upload_url = str_replace(ROOT_PATH,'',$thumbnailfile);
				$upload_url = str_replace(DS,'/',$upload_url);
				exit ( '{"success":true,"picture":"' . $upload_url . '","width":'.$save_width.',"height":'.$save_height.'}' );
			} else {
				exit ( '{"success":false,"info":"上传图片失败"}' );
			}
		} else {
			exit ( '{"success":false,"info":"未知的错误"}' );
		}
	}
}
exit ( '{"success":false,"info":"非法的令牌"}' );			
?>