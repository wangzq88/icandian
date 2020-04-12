<?php 
Yii::import('zii.widgets.CPortlet');
 
class BootstrapPaging extends CPortlet
{
	public $page = 1;
    public $total_page = 1;
	public $url = '';
	public $friendly = false;
    
    public function init()
    {
    	if (empty($this->url)) {
    		if (!preg_match('/page/',$_SERVER['REQUEST_URI'])) {
				if(strpos($_SERVER['REQUEST_URI'],'?') !== false)
    				$this->url = $_SERVER['REQUEST_URI'].'&page=1';
				else
					$this->url = $_SERVER['REQUEST_URI'].'?page=1';
    		} else {
    			$this->url = $_SERVER['REQUEST_URI'];
    		}
    	} 
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
        $this->render('bootstrapPaging');
    }
}
?>