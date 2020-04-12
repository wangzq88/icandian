<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class WeiboUserIdentity extends CBaseUserIdentity
{
	public $username;
	private $_id;
	private $_appid;
	private $_appkey;
	private $_callback;
	private $_scope;
	
	/**
	 * 
	 * 申请新浪微博登录，点击页面的微博登录按钮，要调用这个方法
	 */
	public function redirectWeiboLogin() {
		require_once Yii::app()->basePath . '/extensions/weibo/config.php';
		require_once Yii::app()->basePath . '/extensions/weibo/saetv2.ex.class.php';
		$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
		$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );		
		header("Location: $code_url");	
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
				$mobile = $record->mobile ? $record->mobile:0;
//				$_SESSION['uid'] = $record->uid;
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
            	$this->setState('last_visit', $record->last_visit);            	
            	$this->setState('password_strength', $record->password_strength);
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
	public function weiboCallback()
	{
		require_once Yii::app()->basePath . '/extensions/weibo/config.php';
		require_once Yii::app()->basePath . '/extensions/weibo/saetv2.ex.class.php';	
		$o = new SaeTOAuthV2 ( WB_AKEY, WB_SKEY );
		if (isset ( $_REQUEST ['code'] )) {
			$keys = array ();
			$keys ['code'] = $_REQUEST ['code'];
			$keys ['redirect_uri'] = WB_CALLBACK_URL;
			try {
				$token = $o->getAccessToken ( 'code', $keys );
			} catch ( OAuthException $e ) {
				echo "<h3>error:</h3>" . $e->getCode();                   ;
				echo "<h3>msg  :</h3>" . $e->getMessage();
				exit;				
			}
			if ($token) {
				$_SESSION ['token'] = $token;
				setcookie ( 'weibojs_' . $o->client_id, http_build_query ( $token ) );
			}
			$c = new SaeTClientV2 ( WB_AKEY, WB_SKEY, $_SESSION ['token'] ['access_token'] );
			//$ms = $c->home_timeline (); // done
			$uid_get = $c->get_uid ();
			$_SESSION['openid'] = $uid_get ['uid'];
		}
	}
	
	public function get_weibo_user_info()
	{
		require_once Yii::app()->basePath . '/extensions/weibo/config.php';
		require_once Yii::app()->basePath . '/extensions/weibo/saetv2.ex.class.php';		
		$c = new SaeTClientV2 ( WB_AKEY, WB_SKEY, $_SESSION ['token'] ['access_token'] );
	//	$ms = $c->home_timeline (); // done
	//	$uid_get = $c->get_uid ();
		$user_message = $c->show_user_by_id ( $_SESSION['openid'] );
		return $user_message;
	}
	
}