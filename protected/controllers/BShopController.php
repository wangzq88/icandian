<?php
require Yii::app()->basePath.DS.'config'.DS.'business.php';
class BShopController extends Controller
{
	public $layout='//layouts/business';
	private $_preview_width = SHOP_LOGO_WIDTH;
	private $_preview_height = SHOP_LOGO_HEIGHT;
	private $_ban_pv_w = SHOP_BANNER_WIDTH;
	private $_ban_pv_h = SHOP_BANNER_HEIGHT;
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
                'application.filters.BussinessFilter'
            ),
			'postOnly + delete,create', // we only allow deletion via POST request
		);
	}	
	
	public function actionIndex()
	{
		$uid = (int)Yii::app()->user->id;
		$command = Yii::app()->db->createCommand();
		$shop = $command->select('*')->from('{{shop}}')->where('uid=:uid',array(':uid'=>$uid))->queryRow();	
		if($shop && is_array($shop)) {
			if($shop['shop_opening_hours']) {
				$shop_opening_hours = json_decode($shop['shop_opening_hours'],true);
				$shop['shop_opening_hours'] = array();
				foreach($shop_opening_hours as $key => $item) {
					$shop['shop_opening_hours'][$key] = array();
					$list = explode(' ',$item);
					$tmp = explode('-',$list[0]);
					$shop['shop_opening_hours'][$key][0] = $tmp[0];
					$shop['shop_opening_hours'][$key][1] = $tmp[1];
					$tmp = explode('-',$list[1]);
					$shop['shop_opening_hours'][$key][2] = $tmp[0];
					$shop['shop_opening_hours'][$key][3] = $tmp[1];					
				}
			}
			if($shop['ordering_time']) {
				$ordering_time = json_decode($shop['ordering_time'],true);
				$shop['ordering_time'] = array('--','--','--','--');
				if($ordering_time['1']) {
					$list = explode('-',$ordering_time['1']);
					$shop['ordering_time'][0] = $list[0];
					$shop['ordering_time'][1] = $list[1];
				}
				
				if($ordering_time['2']) {
					$list = explode('-',$ordering_time['2']);
					$shop['ordering_time'][2] = $list[0];
					$shop['ordering_time'][3] = $list[1];
				}				
			}
			//显示菜系信息
			if ($shop['shop_cuisine'] != 0) {
				//查询所有菜系信息
				$cuisines = Cuisine::model()->getAllCuisine();					
				foreach ($cuisines as $cui) {
					if($cui['cuisine_id'] == $shop['shop_cuisine']) {
						$shop['shop_cuisine'] = $cui['cuisine_name'];
						break;
					}
				}
			} else {
				$shop['shop_cuisine'] = '不限';
			}						
			$shop['flag'] = $shop['flag'] > 0 ? '开张':'关闭';	
		}

		$this->render('index',array('shop'=>$shop));
	}
	

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	
	
	public function actionUpdate()
	{
	
	    if($_POST)
	    {
			$shop_id = Yii::app()->user->shop_id;
			$_POST = daddslashes(dstrip_tags($_POST));
			$model=$this->loadModel($shop_id);
			$model->flag = (int)$_POST['flag']; 
//			$model->shop_name = $_POST['shop_name'];
			$model->shop_description = $_POST['shop_description'];
			$model->shop_announcement = $_POST['shop_announcement'];
			$ordering_time = array();
			$ordering_time[1] = $_POST['ordering_time'][0].'-'.$_POST['ordering_time'][1];
			$ordering_time[2] = $_POST['ordering_time'][2].'-'.$_POST['ordering_time'][3];
			$ordering_time = json_encode($ordering_time); 
			$model->ordering_time = $ordering_time;
			if($_POST['shop_opening_hours'] && is_array($_POST['shop_opening_hours'])) {
				$shop_opening_hours = array();
				foreach($_POST['shop_opening_hours'] as $key => $item) {
					$shop_opening_hours[$key] = $item[0].'-'.$item[1].' '.$item[2].'-'.$item[3];			
				}
				$shop_opening_hours = json_encode($shop_opening_hours); 
				$model->shop_opening_hours = $shop_opening_hours;
			}
//			$model->shop_province = $_POST['shop_province'];
//			$model->shop_city = (int)$_POST['shop_city'];
//			$model->shop_region = (int)$_POST['shop_region'];
			$model->shop_tips = $_POST['shop_tips'];		
			$model->update_time = time();
			$model->shop_cuisine = (int)$_POST['shop_cuisine'];
//			$model->uid = (int)Yii::app()->user->id;	
//	        $model->attributes=$_POST;
//	        if($model->validate())
			$model->save();
			header('Location: /index.php?r=bshop/index');
			exit();
	    } else {
			$uid = Yii::app()->user->id;
			$sql = "SELECT * FROM {{shop}} WHERE uid=$uid";
			$command = Yii::app()->db->createCommand($sql);
			$shop = $command->queryRow();
			if($shop && is_array($shop)) {
				if($shop['shop_opening_hours']) {
					$shop_opening_hours = json_decode($shop['shop_opening_hours'],true);
					$shop['shop_opening_hours'] = array();
					foreach($shop_opening_hours as $key => $item) {
						$shop['shop_opening_hours'][$key] = array();
						$list = explode(' ',$item);
						$tmp = explode('-',$list[0]);
						$shop['shop_opening_hours'][$key][0] = $tmp[0];
						$shop['shop_opening_hours'][$key][1] = $tmp[1];
						$tmp = explode('-',$list[1]);
						$shop['shop_opening_hours'][$key][2] = $tmp[0];
						$shop['shop_opening_hours'][$key][3] = $tmp[1];					
					}
				}
				if($shop['ordering_time']) {
					$ordering_time = json_decode($shop['ordering_time'],true);
					$shop['ordering_time'] = array('--','--','--','--');
					if($ordering_time['1']) {
						$list = explode('-',$ordering_time['1']);
						$shop['ordering_time'][0] = $list[0];
						$shop['ordering_time'][1] = $list[1];
					}
					
					if($ordering_time['2']) {
						$list = explode('-',$ordering_time['2']);
						$shop['ordering_time'][2] = $list[0];
						$shop['ordering_time'][3] = $list[1];
					}				
				}
			}
						//查询所有菜系信息
			$cuisines = Cuisine::model()->getAllCuisine();	
			$this->render('update',array('shop'=>$shop,'cuisines' => $cuisines,'preview_width' => $this->_preview_width,
			'preview_height' => $this->_preview_height,'ban_pv_w' => $this->_ban_pv_w,'ban_pv_h' => $this->_ban_pv_h));
	    }
	}	
	
	/* Note: This thumbnail creation script requires the GD PHP Extension.  
		If GD is not installed correctly PHP does not render this page correctly
		and SWFUpload will get "stuck" never calling uploadSuccess or uploadError
	 */	
	public function actionUpdatePicture() {
		$x1 = (int)$_POST['x1'];
		$y1 = (int)$_POST['y1'];
		$x2 = (int)$_POST['x2'];
		$y2 = (int)$_POST['y2'];
		$picture_url = $_POST['picture_path'];
		$fileTypes = array ('jpg', 'jpeg', 'png','gif' ); // File extensions		
		$fileParts = pathinfo ( $picture_url );
		if ($_POST['flag'] == 'logo') {
			$tip_id = 'logo_tip';
			$dialog_id = 'update-logo-view';
			$pic_id = 'shop-logo';
			$sel_id = 'newlogo';
			$pre_w = $this->_preview_width;
			$pre_h = $this->_preview_height;
			$pic_field = 'shop_logo';
			$src_w = SHOP_LOGO_VIEW_WIDTH;
			$src_h = SHOP_LOGO_VIEW_HEIGHT;
		} elseif($_POST['flag'] == 'banner') {
			$tip_id = 'banner_tip';
			$dialog_id = 'update-ad-view';
			$pic_id = 'shop-banner';
			$sel_id = 'newbanner';
			$pre_w = $this->_ban_pv_w;
			$pre_h = $this->_ban_pv_h;
			$pic_field = 'shop_banner';	
			$src_w = SHOP_BANNER_VIEW_WIDTH;
			$src_h = SHOP_BANNER_VIEW_HEIGHT;			
		}
		$html_str = '<script type="text/javascript">
		window.parent.document.getElementById("'.$tip_id.'").innerHTML="{tip}";
		window.parent.jQuery("#'.$tip_id.'").closest(".demo-info").show();		
		setTimeout(\'window.parent.jQuery("#'.$tip_id.'").closest(".demo-info").hide();\',3000);
		</script>';				
		if (!in_array ( strtolower($fileParts ['extension']), $fileTypes )) {
			$tip = '不合法的图片格式';
			exit (str_replace('{tip}',$tip,$html_str));
		}		
		
		if (!is_file(ROOT_PATH.$picture_url)) {
			$tip = '该图片不存在';
			exit (str_replace('{tip}',$tip,$html_str));	
		}
		$success = false;
		$time = time();
		$shop = Shop::model()->findByPk(Yii::app()->user->shop_id);
		//说明是原来的图片，原来的图片只有 960×60（广告牌）或者180*180（Logo） 的像素
		if ($shop->$pic_field == $picture_url) {
			$img_info = getimagesize ( ROOT_PATH.$picture_url );
			fixedThumbnail(ROOT_PATH.$picture_url,0,0,$img_info[0],$img_info[1],TMP_PATH.$picture_url,$src_w,$src_h);
			if (is_file(TMP_PATH.$picture_url)) {
				$picture_url = str_replace(ROOT_PATH,'',TMP_PATH).$picture_url;
			}
		}
		$picture_thumbnail_path = $this->getUploadDir().DS.$time.'.'.$fileParts['extension'];
		$picture_thumbnail_url = $this->getUploadUrl().'/'.$time.'.'.$fileParts['extension'];
		thumbnail_interceptor(ROOT_PATH.$picture_url, $x1, $y1,abs($x2-$x1),abs($y2-$y1),$pre_w,$pre_h,$picture_thumbnail_path);
		if (is_file($picture_thumbnail_path)) {
			//储存 100*100 的相片 
			thumbnail($picture_thumbnail_path,$picture_thumbnail_path,100,100);
			$old_pic = ROOT_PATH.$shop->$pic_field;
			$shop->$pic_field = $picture_thumbnail_url;
			if($shop->save()) {
				if (is_file($old_pic)) {
					unlink($old_pic);
				}
				$success = true;
				$tip = 'Logo 已经成功更新';
				$html_str .= '<script type="text/javascript">
				window.parent.jQuery("#'.$dialog_id.'").dialog("close");
				window.parent.jQuery("#'.$pic_id.'").attr("src","'.$picture_thumbnail_url.'");
				</script>';				
			}
		}
		if (!$success)
			$tip = '未能保存图片，请您再试一次。如果还是失败，请与我们的客服联系！';			
		exit (str_replace('{tip}',$tip,$html_str));	
	}
	
	/**
	 * 
	 * Flash 得到缩略图
	 */
	public function actionGetThumbnail() {
		$image_id = isset($_GET["id"]) ? $_GET["id"] : false;
	
		if ($image_id === false) {
			header("HTTP/1.1 500 Internal Server Error");
			echo "No ID";
			exit(0);
		}
		$dir = TMP_PATH.DS.$image_id;
		$img_info = getimagesize($dir);
		switch($img_info[2]) {
		  case 1:
		    header("Content-type: image/gif");
		    break;
		  case 2:
		    header("Content-type: image/jpeg");
		    break;
		  case 3:
		    header("Content-type: image/png");
		    break;    
		}
		header("Content-length: " . filesize($dir));
		flush();
		readfile($dir);
		exit(0);		
	}
	
	public function actionApplyInfo() {
		$_POST = daddslashes(dstrip_tags($_POST));
		$message = $_POST['title'].'：'.$_POST['message'];
		$sql = "INSERT INTO {{message}} (`send_uid`, `send_name`, `receive_uid`,`receive_name`, `message`, `timestamp`, `flag`) 
		VALUES (".Yii::app()->user->id.",'".Yii::app()->user->username."',".ADMIN_ID.",'".ADMIN_NAME."','".$message."',".time().",1)";
		$command = Yii::app()->db->createCommand($sql);	
		if($command->execute()) {
			$result = array('success' => 1,'info' => '你的申请已经提交，请耐心等候！');
		} else {
			$result = array('success' => 0,'info' => '发生未知的错误，请再试一次！');
		}
		header('content-type: application/json; charset=utf-8'); 
		exit(json_encode($result));		
	}
	
	public function loadModel($id)
	{
		$model=Shop::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}	
	
	protected function getUploadDir() {
		$upload_dir = SHOP_UPLOAD_PATH.DS.(int)Yii::app()->user->shop_id;
		if(!is_dir($upload_dir)) {
			mkdir($upload_dir,0777,true);
		}		
		return $upload_dir;
	}
	
	protected function getUploadUrl() {
		return SHOP_UPLOAD_URL.'/'.(int)Yii::app()->user->shop_id;
	}
	
}