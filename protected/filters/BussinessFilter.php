<?php
class BussinessFilter extends CFilter
{
 	protected function preFilter($filterChain)
    {
        // logic being applied before the action is executed
        if (Yii::app()->user->isGuest) {
        	header('Location: /index.php?r=site/login');
        	return false;
        }
        if (Yii::app()->user->flag != 2) {
        	header('Location: /index.php');
        	return false;
        }
		if(!isset($_SESSION['food_count'])) {
			$command = Yii::app()->db->createCommand('SELECT COUNT(*) AS count FROM {{food}} WHERE shop_id='.Yii::app()->user->shop_id);
			$_SESSION['food_count'] = $command->queryScalar();
			$command->reset();
			$command->text = 'SELECT COUNT(*) AS count FROM {{package}} WHERE shop_id='.Yii::app()->user->shop_id;
			$_SESSION['food_count'] += $command->queryScalar();
			$command->reset();
			$command->text = 'UPDATE {{shop}} SET food_count='.$_SESSION['food_count'].' WHERE shop_id='.Yii::app()->user->shop_id;
			$command->execute();
		}        
        return true; // false if the action should not be executed
    }
 
    protected function postFilter($filterChain)
    {
        // logic being applied after the action is executed
    }
	
} 
?>