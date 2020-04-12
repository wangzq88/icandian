<?php

class ManageController extends Controller
{
	public $layout='//layouts/business';
	
	public function filters()
    {
        return array(
            array(
                'application.filters.BussinessFilter'
            ),
        );
    }
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		//$this->render('index');
		$this->renderPartial('businessIndex');
	}
	
	public function actionNav() {
		$this->render('index');
	}
}

?>