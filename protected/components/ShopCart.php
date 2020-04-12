<?php 
Yii::import('zii.widgets.CPortlet');
 
class ShopCart extends CPortlet
{
    public function init()
    {
 		ob_start();
		ob_implicit_flush(false);
		$this->renderDecoration();
		ob_clean();
    }
 
	/**
	 * Renders the content of the portlet.
	 */
	public function run()
	{
		$this->renderContent();
		$content=ob_get_clean();
		if($this->hideOnEmpty && trim($content)==='')
			return;
		echo $content;
	}
	    
    protected function renderContent()
    {
        $this->render('shopCart');
    }
}
?>