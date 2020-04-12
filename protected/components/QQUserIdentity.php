<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class QQUserIdentity extends CBaseUserIdentity
{
	public $username;
	private $_id;
	private $_appid;
	private $_appkey;
	private $_callback;
	private $_scope;
	
	function __construct($_appid,$_appkey,$_callback,$_scope) {
		$this->_appid =  $_appid;
		$this->_appkey = $_appkey;
		$this->_callback = $_callback;
		$this->_scope = $_scope;
	}
	
	/**
	 * 
	 * 申请QQ登录，点击页面的QQ登录按钮，要调用这个方法
	 */
	public function redirectQQLogin() {
		$_SESSION['state'] = md5(uniqid(rand(), true)); //CSRF protection
		$login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" 
			. $this->_appid . "&redirect_uri=" . urlencode($this->_callback)
			. "&state=".$_SESSION['state']
			. "&scope=".$this->_scope;
		header("Location: $login_url");	
		exit();	
	}	
	
	/**
	 * 
	 * 认证
	 */
	public function authenticate()
	{
 			$record = User::model()->findByPk($this->_id);
 		    if($record===null)
            	$this->errorCode=self::ERROR_USERNAME_INVALID;
        	else
        	{
        		$email = $record->valid_email ? $record->email:'';
				$this->username = $record->username;//设定 Yii::app()->user 的 Name
				//$_SESSION['uid'] = $record->uid;
				$mobile = $record->mobile ? $record->mobile:0;
				$this->_id = $record->uid;//设定 Yii::app()->user 的 ID
            	$this->setState('username', $record->username);
            	$this->setState('email', $email);
            	$this->setState('avatar', $record->avatar);
           	 	$this->setState('mobile', $mobile);
            	$this->setState('integration', $record->integration);
           	 	$this->setState('collection_shop', $record->collection_shop);
            	$this->setState('collection_food', $record->collection_food);           	 	
            	$this->setState('flag', $record->flag);
            	$this->setState('ip', $record->ip1.'.'.$record->ip2.'.'.$record->ip3.'.'.$record->ip4);
            	$this->setState('password_strength', $record->password_strength);
            	$this->setState('last_visit', $record->last_visit);            	
            	$this->setState('timestamp', $record->timestamp);
	            //保存这次登录的IP和时间戳
	            $ip = get_onlineip();
	            if ($ip) {
	            	list($record->ip1,$record->ip2,$record->ip3,$record->ip4) = explode('.',$ip);
	            }
	            $record->last_visit = time();
	            $record->save();               	
				//说明是商家
				if($record->flag == 2) {
					$record = Shop::model()->findByAttributes(array('uid'=>$record->uid));
					$this->setState('shop_id', $record->shop_id);
				}					
            	$this->errorCode=self::ERROR_NONE;
        	} 			
        return !$this->errorCode;		
	}
	
    public function getId()
    {
        return $this->_id;
    }
	
    public function setId($id)
    {
        return $this->_id = $id;
    }		
	
	public function getName()
	{
		return $this->username;
	}	
    //QQ登录成功后的回调地址,主要保存access token。用户登录QQ后重定向会网站，调用以下这个方法
	public function qq_callback()
	{	
		if($_REQUEST['state'] == $_SESSION['state']) //csrf
		{
			$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
				. "client_id=" . $this->_appid. "&redirect_uri=" . urlencode($this->_callback)
				. "&client_secret=" . $this->_appkey. "&code=" . $_REQUEST["code"];
			$response = file_get_contents($token_url);
			if (strpos($response, "callback") !== false)
			{
				$lpos = strpos($response, "(");
				$rpos = strrpos($response, ")");
				$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
				$msg = json_decode($response);
				if (isset($msg->error))
				{
					echo "<h3>error:</h3>" . $msg->error;
					echo "<h3>msg  :</h3>" . $msg->error_description;
					exit;
				}
			}
	
			$params = array();
			parse_str($response, $params);
	
			$_SESSION['access_token'] = $params['access_token'];
			//获取用户标示id
			$_SESSION['openid'] = $this->get_openid();
		}
		else 
		{
			echo("The state does not match. You may be a victim of CSRF.");
		}
	}
	
	protected function get_openid()
	{
		$graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" 
			. $_SESSION['access_token'];
	
		$str  = file_get_contents($graph_url);
		if (strpos($str, "callback") !== false)
		{
			$lpos = strpos($str, "(");
			$rpos = strrpos($str, ")");
			$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
		}
	
		$user = json_decode($str);
		if (isset($user->error))
		{
			echo "<h3>error:</h3>" . $user->error;
			echo "<h3>msg  :</h3>" . $user->error_description;
			exit;
		}
		return $user->openid;
	}
	
	public function get_qq_user_info()
	{
		$get_user_info = "https://graph.qq.com/user/get_user_info?"
			. "access_token=" . $_SESSION['access_token']
			. "&oauth_consumer_key=" . $this->_appid
			. "&openid=" . $_SESSION['openid']
			. "&format=json";
	
		$info = file_get_contents($get_user_info);
		$arr = json_decode($info, true);
	
		return $arr;
	}
	
}