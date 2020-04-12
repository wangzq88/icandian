<?php
Yii::import('zii.widgets.CPortlet');

class ShopHeader extends CPortlet
{
	public $shop;
    public $menu = true;
    public $title;
	
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
        $this->render('shopHeader',array('shop' => $this->shop,'menu' => $this->menu,'title' => $this->title));
    }
}
?>