<?php
class ManageFilter extends CFilter
{
 	protected function preFilter($filterChain)
    {
        // logic being applied before the action is executed
        if (Yii::app()->user->isGuest) {
        	header('Location: /index.php?r=site/login');
        	return false;
        }
        if (Yii::app()->user->flag != 3) {
        	header('Location: /index.php');
        	return false;
        }
        return true; // false if the action should not be executed
    }
 
    protected function postFilter($filterChain)
    {
        // logic being applied after the action is executed
    }
	
} 
?>