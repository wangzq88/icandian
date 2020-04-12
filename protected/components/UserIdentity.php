<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
/*		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;*/
 		$record=User::model()->findByAttributes(array('email'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($record->password!==md5($this->password.$record->salt))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
        	$this->username = $record->username;
            $this->_id = $record->uid;//设定 Yii::app()->user 的 ID
            $mobile = $record->mobile ? $record->mobile:0;
			//$_SESSION['uid'] = $record->uid;
            $this->setState('username', $record->username);
            $this->setState('email', $record->email);
            $this->setState('avatar', $record->avatar);
            $this->setState('integration', $record->integration);
            $this->setState('collection_shop', $record->collection_shop);
            $this->setState('collection_food', $record->collection_food);
            $this->setState('mobile', $mobile);
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
				//$_SESSION['shop_id'] = $record->shop_id;
			}			
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;		
	}
	
    public function getId()
    {
        return $this->_id;
    }	
}