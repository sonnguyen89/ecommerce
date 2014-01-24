<?php class VS_Ajax_Model_Observer{

// Get all event control dispatch
    public function getListproduct(Varien_Event_Observer $event){   
        $controller = $event->getControllerAction();
		$param = array();
		$ajaxtoolbar	=	 Mage::app()->getRequest()->getParam('ajaxtoolbar');
		$layout = Mage::app()->getLayout();
		$blocks = array();
		if($ajaxtoolbar==1){
			$templ = $layout->getBlock('product_list');
			if($templ){
				@header('Content-type: application/json');
				$blocks['toolbarlistproduct']	=	$templ->toHtml();
				echo json_encode($blocks);
			}
			$templ = $layout->getBlock('search_result_list');
			if($templ){
				@header('Content-type: application/json');
				$blocks['toolbarlistproduct']	=	$templ->toHtml();
				echo json_encode($blocks);
			}
			exit;
		}
		
    }
	
} 

