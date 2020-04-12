<?php

class SiteController extends Controller
{
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
                'application.filters.FrontFilter'
            ),
			array(
				'COutputCache +index',
				'duration'=>3600*6,
				'varyByParam'=>array('area','section','page','shop_name','cuisine')
			),		
		);
	}	
		
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function renderDynamicTop() {
		return $this->renderPartial('_site_top',NULL,true);
	}	
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$time = time();
		$city_id = 39;//默认为广州
		$_GET['area'] = isset($_GET['area']) ? $_GET['area']:(isset($_GET['section']) ? 0:74);//长寿路
		$area_list = array();//地段
		$section_list = array();//具体路段
		$region_list = Region::model()->published()->orderDes()->findAll('city_id=:city_id',array(':city_id'=>$city_id));
		$region_id_list = array();
		$area_id_list = array();
		if ($region_list && is_array($region_list)) {
			foreach ($region_list as $region) {
				$region_id_list[] = $region['region_id'];
			}
			//获取该区域所有的地点
			$result_list = Area::model()->getPublishedAreaListByRegionIDs($region_id_list);	

			foreach ($result_list as $result) {
				$area_id_list[] = $result['area_id'];
				
				if(!isset($area_list[$result['region_id']])) {
					$area_list[$result['region_id']] = array();
				} 
				array_push($area_list[$result['region_id']],$result);
			}
			
			$result_list = Sections::model()->getAllSectionsByAreaID($area_id_list);
			foreach ($result_list as $result) {
				if(!isset($section_list[$result['area_id']])) {
					$section_list[$result['area_id']] = array();
				} 
				array_push($section_list[$result['area_id']],$result);				
			}
		}
		$limit = 12;
		$con = '';
		$shop_name = trim(strip_tags($_GET['shop_name']));
		if ($shop_name) {
			$con = " AND shop_name LIKE '%$shop_name%' ";
		}
		if ($_GET['area'] > 0) {
			$con .= " AND shop_area = '".(int)$_GET['area']."' ";
		}
		if ($_GET['section'] > 0) {
			$con .= " AND shop_section = '".(int)$_GET['section']."' ";
		}
		if ($_GET['cuisine'] > 0) {
			$con .= " AND shop_cuisine = '".(int)$_GET['cuisine']."' ";
		}
		$_REQUEST['page'] = $_REQUEST['page'] >= 1 ? intval($_REQUEST['page']):1;
		$start = ($_REQUEST['page'] - 1) * $limit;			
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{shop}} WHERE status=1 $con LIMIT $start,$limit";
		$command = Yii::app()->db->createCommand($sql);		
		$rows = $command->queryAll();
		$command->reset();  // clean up the previous query
		$command->text = 'SELECT FOUND_ROWS()';
		$total = $command->queryScalar();			
		$total_page = ceil($total/$limit);
		if ($_REQUEST['page'] > $total_page) {
			$_REQUEST['page'] = $total_page;
		}		
		//查询所有菜系信息
		$cuisines = Cuisine::model()->getAllCuisine();	
		//现在的时间段
		$now = date('H:i');
		foreach ($rows as &$row) {
			$row['shop_logo'] = get_thumbnail_path($row['shop_logo'],100,100,false);
			$row['open'] = false;//是否真正营业状态当中,默认为否
			$shop_opening_hours = json_decode($row['shop_opening_hours'],true);
			//星期几的营业时间,星期中的第几天，数字表示.1（表示星期一）到 7（表示星期天）
			$shop_opening_hours = $shop_opening_hours[date('N')];
			$row['shop_row_opening'] =  $shop_opening_hours;
			//上午和下午的营业时间段
			$list = explode(' ',$shop_opening_hours);
			//上午的营业时间段
			$tmp = explode('-',$list[0]);
			//下午的营业时间
			$tmp1 = explode('-',$list[1]);			
			if ($now <= $tmp[1]) {
				$row['now_opening_hours'] = $tmp[0].'～'.$tmp[1];
				//处于这个时间段，表示正在营业当中
				if ($now > $tmp[0]) {
					$row['open'] = true;
				}
			} else {
				$row['now_opening_hours'] = $tmp1[0].'～'.$tmp1[1];
				if ($now >= $tmp1[0] && $now < $tmp1[1]) {
					$row['open'] = true;
				}
			}
			//说明中午没有休息，全天运营
			if ($tmp[1] == $tmp1[0]) {
				$row['now_opening_hours'] = $tmp[0].'～'.$tmp1[1];
			}
			//显示详细的营业时间
			$row['shop_opening_hours'] = $tmp[0].'～'.$tmp[1];
			if ($tmp1[0] != $tmp1[1]) {//如果前后时间相等，说明下午没有营业
				$row['shop_opening_hours'] .= ' '.$tmp1[0].'～'.$tmp1[1]; 
			}
			if ($tmp[1] == $tmp1[0]) {
				$row['shop_opening_hours'] = $tmp[0].'～'.$tmp1[1];
			}
			//显示详细的最佳订餐时间，包括上午和下午
			if($row['ordering_time']) {
				$ordering_time = json_decode($row['ordering_time'],true);
				if($ordering_time['1']) {
					$list = explode('-',$ordering_time['1']);
					$row['ordering_time'] = $list[0].'～'.$list[1];
				}
				//下午订餐时间
				if($ordering_time['2']) {
					$list1 = explode('-',$ordering_time['2']);
					if ($list[1] == $list1[0]) {//说明全天接受订餐，中间没空格的时间
						$row['ordering_time'] = $list[0].'～'.$list1[1];
					} elseif ($list1[0] != $list1[1]) {//不相等说明下午接受预订
						$row['ordering_time'] .= ' '.$list1[0].'～'.$list1[1];
					}
				}				
			}	
			//显示现在订餐时间
			if ($now <= $tmp[1]) {//如果现在处于上午的营业时间，显示上午的订餐时间
				$row['now_ordering_time'] = $list[0].'～'.$list[1];
			} else {
				$row['now_ordering_time'] = $list1[0].'～'.$list1[1];
			}					
			//如果是在7天之内开张的，显示“新”
			if ($row['timestamp'] + 7*24*3600 > $time) {
				$row['new'] = true;
			} else {
				$row['new'] = false;
			}
			//显示菜系信息
			if ($row['shop_cuisine'] != 0) {
				foreach ($cuisines as $cui) {
					if($cui['cuisine_id'] == $row['shop_cuisine']) {
						$row['shop_cuisine'] = $cui['cuisine_name'];
						break;
					}
				}
			} else {
				$row['shop_cuisine'] = '不限';
			}
		}
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index',array(
			'shop_list' => $rows,
			'region_list' => $region_list,
			'area_list' => $area_list,
			'section_list' => $section_list,
			'cuisines' => $cuisines,
			'page' => $_REQUEST['page'],
			'total_page' => $total_page		
		));
	}
	
	public function actionUpdatePassword()
	{
		require_once Yii::app()->basePath . '/extensions/rsa/myrsa.php';
		$result = array ('success' => 0, 'info' => '不能为空' );
		$old = $_POST['old_password'];
		$new = $_POST['new_password'];
		$uid = Yii::app()->user->id;
		$old = decryptPassword($old);
		$record = User::model()->findByPk($uid);
		if($record->password !== md5($old.$record->salt)) {
			$result['info'] = '输入有误！原密码不正确';
		} else {	
			$new = decryptPassword($new);
			$record->password = md5($new.$record->salt);
			$result['success'] = $record->save();
			$result['info'] = '恭喜！你的密码已成功修改';
		}
		header ( "Content-type: text/json; charset=utf-8" );
		exit ( json_encode ( $result ) );			
	}
	
	public function actionPassword()
	{
		$this->render('password');		
	}
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	public function actionAjaxLogin()
	{
		// collect user input data
		if($_POST)
		{
			$model = new LoginForm;
			$result = array('success' => false,'info' => '您输入的帐号或密码不正确，请重新输入。');
			$model->attributes = $_POST;
			$model->password = $_POST['password'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
				$result['success'] = true;
				$result['info'] = '登录成功，正在为您转到新的网址...';
			} 
			header('content-type: application/json; charset=utf-8'); 
			exit(json_encode($result));				
		}
		
	}
	
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		//@session_start();
		if(!Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->homeUrl);
		}
		$model=new LoginForm;

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];
			$model->password = $_POST['LoginForm']['encryption'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) 
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	/**
	 * 
	 * 申请QQ登录
	 */
	public function actionRedirectQQLogin() {
		//@session_start();
		$model=new QQLoginForm;
		$model->redirectQQLogin();
	}
	
	/**
	 * 
	 * 用户登录QQ后重定向到本地址
	 */
	public function actionQQCallback() {
		//QQ登录成功后的回调地址,主要保存access token
		$model=new QQLoginForm;
		$model->qqCallback();
	}
	
	public function actionMixedLogin() {
		if ($_SESSION['openid']) {
			$model=new LoginForm;
			$this->render('mixedlogin',array('model'=>$model));
		} else {
			die('非法访问!');
		}
	}
	/**
	 * 
	 * 点击“一键创建新帐号”按钮，通过QQ创建新的帐户
	 */
	public function actionCreateNewUserByQQ() {
		$model=new QQLoginForm;
		if($model->createNewUserByQQ()) {
			$this->redirect(Yii::app()->homeUrl);
		} else {
			$this->redirect(Yii::app()->user->returnUrl);
		}
	}	
	/**
	 * 
	 * 已有帐户绑定QQ
	 */	
	public function actionBindUserByQQ() {
		$model=new QQLoginForm;
		$model->email = $_POST['LoginForm']['email'];
		$model->password = $_POST['LoginForm']['encryption'];		
		if($model->bindUserByQQ()) {
			$this->redirect(Yii::app()->homeUrl);
		} else {
			$this->redirect(Yii::app()->user->returnUrl);
		}
	}
	
	/**
	 * 
	 * 申请新浪微博登录
	 */
	public function actionRedirectWeiboLogin() {
		$model=new WeiboLoginForm;
		$model->redirectWeiboLogin();
	}
	/**
	 * 
	 * 用户登录微博后重定向到本地址
	 */	
	public function actionWeiboCallback() {
		$model=new WeiboLoginForm;
		$model->weiboCallback();
	}
	/**
	 * 
	 * 点击“一键创建新帐号”按钮，通过微博创建新的帐户
	 */
	public function actionCreateNewUserByWeibo() {
		$model=new WeiboLoginForm;
		if($model->createNewUserByWeibo()) {
			$this->redirect(Yii::app()->homeUrl);
		} else {
			$this->redirect(Yii::app()->user->returnUrl);
		}
	}
	/**
	 * 
	 * 已有帐户绑定微博
	 */	
	public function actionBindUserByWeibo() {
		$model=new WeiboLoginForm;
		$model->email = $_POST['LoginForm']['email'];
		$model->password = $_POST['LoginForm']['encryption'];
		if($model->bindUserByWeibo()) {
			$this->redirect(Yii::app()->homeUrl);
		} else {
			$this->redirect(Yii::app()->user->returnUrl);
		}
	}	
}
?>