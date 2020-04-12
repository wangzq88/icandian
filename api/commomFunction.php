<?php
/*******************************
 * 自动生成略缩小图，还可以加指定水印
 * 同时也是可以做图像格式转换，把GIF转为 JPEG 格式
 * 参数1： $_file_source	上传的源文件
 * 参数2： $_file_target	保存在服务器上的目标文件
 * 参数3： $_save_width		存储文件的宽度
 * 参数4： $_save_height	存储文件的高度
 * 参数5： $_add_watermark	是否加水印
 * 返回： 0 成功 
          1 文件不存在
          2 不合法的图片格式
		  3  图像处理失败
 * 2006/11/27 11:37 hao
 ******************************/
function thumbnail($_file_source, $_file_target, $_save_width = '0', $_save_height = '0', $_watermark = '') {
	if (empty ( $_file_source ) || ! file_exists ( $_file_source )) {
		return '1';
	}
	$fileTypes = array ('jpg', 'jpeg', 'gif', 'png' ); // File extensions
	$_file_dirname = pathinfo ( $_file_target, PATHINFO_DIRNAME ); // 得知这个附件的文件目录，里面有大写字母!
	$_file_basename = strtolower ( pathinfo ( $_file_target, PATHINFO_BASENAME ) ); // 得知这个附件的文件基本名称
	$_file_extension = strtolower ( pathinfo ( $_file_target, PATHINFO_EXTENSION ) ); // 得知这个附件的文件类型
	$_file_source_extension = strtolower ( pathinfo ( $_file_source, PATHINFO_EXTENSION ) ); // 源图像文件的文件类型
	$_file_basename = substr ( $_file_basename, 0, strpos ( $_file_basename, $_file_extension ) - 1 );
	if (! in_array ( $_file_extension, $fileTypes )) {
		return '2';
	}
	
	list ( $_ori_width, $_ori_height ) = @getimagesize ( $_file_source );
	if ($_ori_height == 0)
		return 3; //-3 读取图像文件失败 因为分母不能为零
	

	// 如果源图是 GIF 格式，则需要转换为 JPEG 格式，目标扩展名需要更换
	if ($_file_source_extension == 'gif')
		$_file_extension = 'jpg';
	
		// 如果是原图，则文件不变，否则需要在文件名后加如  _80x80  的格式
	if ($_save_width == $_ori_width && $_save_height == $_ori_height) {
		$_file_target = $_file_dirname . '/' . $_file_basename . '.' . $_file_extension;
	} else {
		$_file_target = $_file_dirname . '/' . $_save_width . '_' . $_save_height . '_' . $_file_basename . '.' . $_file_extension;
	}
	if (!is_dir($_file_dirname)) {
		mkdir($_file_dirname, 0777,true);
	}		
	$_source_width = $_ori_width; //需要切原图的宽度
	$_source_height = $_ori_height; //需要切原图的高度
	$_source_left = 0; //需要切原图的左边
	$_source_top = 0; //需要切原图的上边
	$_target_width = $_save_width;
	$_target_height = $_save_height;
	if ($_target_width == 0)
		$_target_width = $_source_width; // 分母不能为空，取源图宽度
	if ($_target_height == 0)
		$_target_height = $_source_height; // 分母不能为空，取源图高度
	

	$_source_ratio = round ( $_ori_width / $_ori_height, 1 ); //原图的宽高长
	$_target_ratio = round ( $_target_width / $_target_height, 1 ); //目标的宽高比，以这个为标准
	

	if ($_source_ratio < $_target_ratio) //宽度小于高度，需要调整高度
{
		$_source_height_new = round ( ($_source_width * $_target_height) / $_target_width );
		$_height_cut = $_source_height - $_source_height_new;
		$_source_top += round ( $_height_cut / 2 );
		$_source_height = $_source_height_new;
	}
	if ($_source_ratio > $_target_ratio) //宽度大于高度，需要调整宽高
{
		$_source_width_new = round ( ($_source_height * $_target_width) / $_target_height );
		$_width_cut = $_source_width - $_source_width_new;
		$_source_left += round ( $_width_cut / 2 );
		$_source_width = $_source_width_new;
	}
	
	switch ($_file_extension) {
		case 'gif' :
			$simage = @imagecreatefromgif ( $_file_source );
			break;
		case 'jpeg' :
		case 'jpg' :
			$simage = @imagecreatefromjpeg ( $_file_source );
			break;
		case 'png' :
			$simage = @imagecreatefrompng ( $_file_source );
			break;
		default :
			return '2';
	}
	if ($simage) {
		$nimage = @imagecreatetruecolor ( $_target_width, $_target_height );
		@imagecopyresampled ( $nimage, $simage, 0, 0, $_source_left, $_source_top, $_target_width, $_target_height, $_source_width, $_source_height );
		// 如果需要加水印
		if ($_watermark != '') {
			$white = imagecolorallocate ( $nimage, 255, 255, 255 );
			$black = imagecolorallocate ( $nimage, 0, 0, 0 );
			$red = imagecolorallocate ( $nimage, 255, 0, 0 );
			imagefill ( $nimage, 0, 0, $white );
			
			//			imagecopy($nimage,$simage,0,0,0,0,$_source_width,$_source_height); 
			//@imagecopyresampled($nimage,$simage,0,0,$_source_left,$_source_top,$_target_width,$_target_height,$_source_width,$_source_height);
			

			imagefilledrectangle ( $nimage, 1, $_target_height - 15, 80, $_target_height, $white );
			
			// 判断加水印的方式
			if (strpos ( $_watermark, '.' ) > 0 && strpos ( $_watermark, '/' ) > 0) {
				$_watertype = 2;
				$_file_watermark_extension = strtolower ( pathinfo ( $_watermark, PATHINFO_EXTENSION ) ); // 得知这个附件的文件类型
			

			} else {
				$_watertype = 1;
			}
			switch ($_watertype) {
				case 1 : //加水印字符串 
					imagestring ( $nimage, 2, 3, $_target_height - 15, $_watermark, $black );
					break;
				case 2 : //加水印图片 
					//					$simage1 =imagecreatefromgif( $_watermark ); 
					switch ($_file_watermark_extension) {
						case 'gif' :
							$wimage = @imagecreatefromgif ( $_watermark );
							break;
						case 'jpeg' :
						case 'jpg' :
							$wimage = @imagecreatefromjpeg ( $_watermark );
							break;
						case 'png' :
							$wimage = @imagecreatefrompng ( $_watermark );
							break;
						default :
							return - 1;
					}
					
					imagecopy ( $nimage, $wimage, 0, 0, 0, 0, 85, 15 );
					imagedestroy ( $wimage );
					break;
			} // 加水印处理完毕
		}
		
		$result = @imagejpeg ( $nimage, $_file_target );
		@imagedestroy ( $nimage );
	}
	
	@imagedestroy ( $simage );
	if ($result)
		return $_file_target;
	else
		return 3; //图像处理失败
}

/**
 * 
 * 缩略图
 * @param string $image 源图片的URL
 * @param int $x1 源图片的 x 坐标
 * @param int $y1 源图片的 y 坐标
 * @param int $width 要截取源图片的宽度
 * @param int $height 要截取源图片的高度
 * @param int $newImageWidth 新图片的宽度
 * @param int $newImageHeight 新图片的高度
 */
function thumbnail_interceptor($image, $x1, $y1, $width, $height, $newImageWidth = 100, $newImageHeight = 100,$thumbnail_path='') {
	//得到图片的相关信息
	$img_info = getimagesize ( $image );
	$image_type = $img_info ['mime'];
	switch ($image_type) {
		case 'image/gif' :
			//从 GIF 文件或 URL 新建一图像，返回一图像标识符，代表了从给定的文件名取得的图像。
			$source = imagecreatefromgif ( $image );
			break;
		case 'image/jpeg' :
			$source = imagecreatefromjpeg ( $image );
			break;
		case 'image/png' :
			$source = imagecreatefrompng ( $image );
			break;
	}
	//新建一个真彩色图像，代表了一幅大小为 $newImageWidth 和 $newImageHeight 的黑色图像
	$newImage = imagecreatetruecolor ( $newImageWidth, $newImageHeight );
	//重采样拷贝部分图像并调整大小
	imagecopyresampled ( $newImage, $source, 0, 0, $x1, $y1, $newImageWidth, $newImageHeight, $width, $height );
	//得到原来图片的存放路径
	$img_path_info = pathinfo ( $image );
	//设置新图片的保存路径
	if (!$thumbnail_path) {
		$fileName = time () . rand () . '.' . $img_path_info ['extension'];
		$thumbnail_path = $img_path_info ['dirname'] . DS . $fileName;
	} 
	if (!is_dir($img_path_info ['dirname'])) {
		mkdir($img_path_info ['dirname'], 0700,true);
	}	
	switch ($image_type) {
		case 'image/gif' :
			//以 GIF 格式将图像输出到浏览器或文件
			imagegif ( $newImage, $thumbnail_path );
			break;
		case 'image/jpeg' :
			imagejpeg ( $newImage, $thumbnail_path, 100 );
			break;
		case 'image/png' :
			imagepng ( $newImage, $thumbnail_path );
			break;
	}
	return $thumbnail_path;
}

/**
 * 
 * 缩略图
 * @param string $image 源图片的URL
 * @param int $x1 源图片的 x 坐标
 * @param int $y1 源图片的 y 坐标
 * @param int $width 要截取源图片的宽度
 * @param int $height 要截取源图片的高度
 * @param int $newImageWidth 新图片的宽度
 * @param int $newImageHeight 新图片的高度
 */
function fixedThumbnail($image,$x1,$y1,$width,$height,$thumbnail_path='',$newImageWidth = 100,$newImageHeight = 100) {
	//得到图片的相关信息
	$img_info = getimagesize ($image);
	$image_type = $img_info ['mime'];
	switch ($image_type) {
		case 'image/gif' :
			//从 GIF 文件或 URL 新建一图像，返回一图像标识符，代表了从给定的文件名取得的图像。
			$source = imagecreatefromgif ( $image );
			break;
		case 'image/jpeg' :
			$source = imagecreatefromjpeg ( $image );
			break;
		case 'image/png' :
			$source = imagecreatefrompng ( $image );
			break;
	}
	//新建一个真彩色图像，代表了一幅大小为 $newImageWidth 和 $newImageHeight 的黑色图像
	$newImage = imagecreatetruecolor ( $newImageWidth, $newImageHeight );
	//重采样拷贝部分图像并调整大小
	imagecopyresampled ( $newImage, $source, 0, 0, $x1, $y1, $newImageWidth, $newImageHeight, $width, $height );
	//得到原来图片的存放路径
	$img_path_info = pathinfo ( $image );
	//设置新图片的保存路径
	if (!$thumbnail_path) {
		$fileName = time().rand().'.'.$img_path_info['extension'];
		$thumbnail_path = $img_path_info['dirname'].DS.$fileName;
	}
	$dir = pathinfo($thumbnail_path,PATHINFO_DIRNAME);
	if (!is_dir($dir)) {
		mkdir($dir, 0700,true);
	}
	switch ($image_type) {	
		case 'image/gif' :
		//以 GIF 格式将图像输出到浏览器或文件
			imagegif ( $newImage, $thumbnail_path );
		break;
		case 'image/jpeg' :
			imagejpeg ( $newImage, $thumbnail_path, 100 );
		break;
		case 'image/png' :
			imagepng ( $newImage, $thumbnail_path );
		break;
	}
	return $thumbnail_path;	
}
	
function get_thumbnail_path($_file_source,$width,$height,$sys = true) {
	if (empty ( $_file_source ) || ! is_file ( $_file_source )) {
		$_file_source = $_SERVER['DOCUMENT_ROOT'].$_file_source;
		if (!is_file ( $_file_source ))
			return '';
	} 
	$_file_source = str_replace(DIRECTORY_SEPARATOR,'/',$_file_source);
	$fileTypes = array ('jpg', 'jpeg', 'gif', 'png' ); // File extensions
	$_file_dirname = pathinfo ( $_file_source, PATHINFO_DIRNAME ); // 得知这个附件的文件目录，里面有大写字母!
	$_file_basename = strtolower ( pathinfo ( $_file_source, PATHINFO_BASENAME ) ); // 得知这个附件的文件基本名称
	$_file_extension = strtolower ( pathinfo ( $_file_source, PATHINFO_EXTENSION ) ); // 得知这个附件的文件类型
	$_file_basename = substr ( $_file_basename, 0, strpos ( $_file_basename, $_file_extension ) - 1 );
	if (! in_array ( $_file_extension, $fileTypes )) {
		return '';
	}
	$_file_target = $_file_dirname . '/' . $width . '_' . $height . '_' . $_file_basename . '.' . $_file_extension;		
	if (!$sys) {
		if (!is_file ( $_file_target ))
			return '';			
		$_file_target = str_replace($_SERVER['DOCUMENT_ROOT'],'',$_file_target);
	}
	return $_file_target;
}

function get_onlineip() {
	$onlineip = '';
	if (getenv ( 'HTTP_CLIENT_IP' ) && strcasecmp ( getenv ( 'HTTP_CLIENT_IP' ), 'unknown' )) {
		$onlineip = getenv ( 'HTTP_CLIENT_IP' );
	} elseif (getenv ( 'HTTP_X_FORWARDED_FOR' ) && strcasecmp ( getenv ( 'HTTP_X_FORWARDED_FOR' ), 'unknown' )) {
		$onlineip = getenv ( 'HTTP_X_FORWARDED_FOR' );
	} elseif (getenv ( 'REMOTE_ADDR' ) && strcasecmp ( getenv ( 'REMOTE_ADDR' ), 'unknown' )) {
		$onlineip = getenv ( 'REMOTE_ADDR' );
	} elseif (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], 'unknown' )) {
		$onlineip = $_SERVER ['REMOTE_ADDR'];
	}
	return $onlineip;
}

function get_expression_composition($expression) {
	//是否由数字组成
	$pattern = '/^\d+$/';
	if (preg_match ( $pattern, $expression )) {
		return 1;
	}
	//是否由字母组成
	$pattern = "/^[a-z]+$/i";
	if (preg_match ( $pattern, $expression )) {
		return 2;
	}
	//是否由字母，数字或下划线字符组成
	$pattern = '/^\w+$/i';
	if (preg_match ( $pattern, $expression )) {
		return 3;
	}
	//是否由以下特殊字符组成
	$pattern = '/^[A-Za-z0-9!@#+=_-~`$%^&*().?\/\'\"<>\\\{}\[\]:;|]+$/';
	if (preg_match ( $pattern, $expression )) {
		return 4;
	}
	return 5;
}

function is_valid_email($email) {
	$pattern = '/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.(?:[A-Z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)$/i';
	if (preg_match ( $pattern, $email )) {
		return true;
	}
	return false;
}

/**
 * 返回字符串，该字符串为了数据库查询语句等的需要在某些字符前加上了反斜线。这些字符是单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）。
 * @author Discuz!
 * @param  string|Array $string  要转义的变量，类似 $_GET,$_POST
 * @return mixed
 */
function daddslashes($string) {
    if (is_array($string)) {
        $keys = array_keys($string);
        foreach ($keys as $key) {
            $val = $string[$key];
            unset($string[$key]);
            $string[addslashes($key)] = daddslashes($val);
        }
    } else {
        $string = addslashes($string);
    }
    return $string;
}

/**
 * 
 * 返回字符串，返回恢复转义前一个字符串。 （\'变成了'等等。）双反斜杠（\ \）到一个单一的反斜杠（\）。
 * @author Discuz!
 * @param string|Array $string
 * @return mixed
 */
function dstripslashes($string) {
    if (empty($string))
        return $string;
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = dstripslashes($val);
        }
    } else {
        $string = stripslashes($string);
    }
    return $string;
}

function dstrip_tags($string) {
   if (is_array($string)) {
        $keys = array_keys($string);
        foreach ($keys as $key) {
            $val = $string[$key];
            unset($string[$key]);
            $string[$key] = dstrip_tags($val);
        }
    } else {
        $string = strip_tags($string);
    }
    return $string;
}
?>