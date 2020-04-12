<?php
class QQLoginForm extends CFormModel
{
	private $_identity;
	private $_id;
	public $email;
	public $password;
	
	function __construct() {
		require_once Yii::app()->basePath . '/extensions/qq/comm/config.php';
		$this->_identity=new QQUserIdentity($appid,$appkey,$callback,$scope);
		parent::__construct();
	}	

	
	public function login($uid)
	{
		$this->_identity->setId($uid);//设置要登录的uid
		$this->_identity->authenticate();
		if($this->_identity->errorCode === QQUserIdentity::ERROR_NONE)
		{
			Yii::app()->user->login($this->_identity);
			return true;
		}
		else
			return false;
	}

	public function redirectQQLogin() {
		$this->_identity->redirectQQLogin();
	}
	
	public function qqCallback() {
		$this->_identity->qq_callback();
		$user = ExtUsers::model()->findByAttributes(array('uid'=>$_SESSION['openid'],'flag' => 2));
 		if($user == null) {
 			$user_message = $this->_identity->get_qq_user_info();
 			$params = '{"gender":"'.$user_message ['gender'].'","figureurl":"'.$user_message ['figureurl'].'","figureurl_1":"'.
			$user_message ['figureurl_1'].'","figureurl_2":"'.$user_message ['figureurl_2'].'","nickname":"'.$user_message ['nickname'].'"}';			
 			$model = new ExtUsers;
 			$model->uid = $_SESSION['openid'];
 			$model->timestamp = time();
 			$model->flag = '2';
 			$model->params = $params;
 			if($model->save()) {
 				setcookie('username',$user_message ['nickname'],0,'/');
 				setcookie('avatar',$user_message ['figureurl_1'],0,'/');
				exit('<script type="text/javascript">opener.location.href="/index.php?r=site/createNewUserByQQ";window.close();</script>');
 			}
 		} elseif(!$user->user_uid) {
 			$user_message = json_decode($user->params,true);
 			setcookie('username',$user_message ['nickname'],0,'/');
 			setcookie('avatar',$user_message ['figureurl_1'],0,'/'); 			
			exit('<script type="text/javascript">opener.location.href="/index.php?r=site/createNewUserByQQ";window.close();</script>');
		} elseif($this->login($user->user_uid)) {
			exit('<script type="text/javascript">opener.location.href="'.Yii::app()->user->returnUrl.'";window.close();</script>');	
		}	
		exit('<script type="text/javascript">opener.location.href="/index.php?r=site/login";window.close();</script>');
	}
	
	/**
	 * 
	 * 点击“一键创建新帐号”按钮，通过QQ创建新的帐户
	 */
	public function createNewUserByQQ() {
		
		if (!Yii::app()->user->id && $_SESSION['openid']) {
			$user = ExtUsers::model()->findByAttributes(array('uid'=>$_SESSION['openid'],'flag' => 2));
			if ($user) {
				$password = $rand ='';
				for($i=0;$i<6;$i++) {
					$password .= dechex(rand(1,15));
					$rand .= dechex(rand(1,15));
				}
				$time = time();				
				$user_message = json_decode($user['params'],true);
				$model = new User;
				$model->username = $user_message['nickname'];
				$model->salt = $rand;
				$model->password = md5($password.$rand);
				$model->email = $_SESSION['openid'].'@qq.com';
				$model->valid_email = 0;
				$model->password_strength = get_expression_composition($password);
				$model->flag = '1';
				$model->avatar = $user_message['figureurl_1'];
				$model->gender = $user_message['gender'] == '男' ? '1':'2';
				$model->timestamp = $time;
				if ($model->save()) {
					$user->user_uid = $model->uid; 
					$message = new Message;
					$message->send_uid = ADMIN_ID;
					$message->send_name = ADMIN_NAME;
					$message->receive_uid = $model->uid;
					$message->receive_name = $model->username;
					$message->message = "尊敬的用户：您好!欢迎注册iCanDian。您的初始密码为$password";
					$message->timestamp = $time;
					$message->flag = '1';
					$message->save();
					if($user->save()) {
						$this->login($user->user_uid);
						return true;
					}
				}
			} 
		} 
		return false;
	}
	
	public function bindUserByQQ() {
		require_once Yii::app()->basePath . '/extensions/rsa/myrsa.php';
		$this->password = decryptPassword($this->password);
		$this->_identity=new UserIdentity($this->email,$this->password);
		$this->_identity->authenticate();
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration = 3600*24*30; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			$user = ExtUsers::model()->findByAttributes(array('uid'=>$_SESSION['openid'],'flag' => 2));
			$user->user_uid = Yii::app()->user->id;
			if($user->save()) 
				return true;
		}
		return false;	
	}	
	
}
?>