<?php
class FrontFilter extends CFilter
{
 	protected function preFilter($filterChain)
    {
       //用于客户端，如果打开新的链接需要登录，则在原页面出现登录框
		if (Yii::app()->user->isGuest)
			setcookie('is_login','0',0,'/');	
		else 
			setcookie('is_login','1',0,'/');
		return true;
    }
 
    protected function postFilter($filterChain)
    {
        // logic being applied after the action is executed
    }
	
} 
?>