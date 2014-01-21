<?php
class Balance_Deployment_Adminhtml_DeploymentController
	extends Mage_Adminhtml_Controller_Action
{
 	protected function _initAction()
    {
    	//set active menu, title
        $this->_title($this->__('Actions'))
             ->loadLayout()
             ->_setActiveMenu('balance/deployment_server');

        return $this;
    }
	
	public function indexAction()
	{
		$this->_initAction()
             ->_title($this->__('Actions'))
             ->_addBreadcrumb($this->__('Actions'), $this->__('Actions'));
             
		$services = array();
		
		if(Mage::helper('deployment')->canGit()){
			$git = Mage::getModel('deployment/service_git');
			$services[$git->getName()] = $git; 
		}
		
		if(Mage::helper('deployment')->canApc()){
			$apc = Mage::getModel('deployment/service_apc');
			$services[$apc->getName()] = $apc;
		}
		
		if(Mage::helper('deployment')->canMemcached()){
			$memcached = Mage::getModel('deployment/service_memcached');
			$services[$memcached->getName()] = $memcached;
		}
		
		if(Mage::helper('deployment')->canVarnish()){
			$varnish = Mage::getModel('deployment/service_varnish');
			$services[$varnish->getName()] = $varnish;
		}
		
		Mage::register('services', $services);
		
		$this->renderLayout();
	}
	
	public function executeAction()
	{
		$request = $this->getRequest();
		$service = strtolower($request->getParam('service'));
		$cmd = $request->getParam('cmd');
		$param = $request->getParam('param');
		
		//get parameter object
		$paramObject = new Varien_Object();
		$paramObject->param = $param;
		$paramObject->cmd = $cmd;
		
		//init output renderer
		$outputRenderer = Mage::getModel("deployment/itemrenderer_output");
		//init output
		$output = '';
		//get service object
		$serviceObject = Mage::getModel('deployment/service_'.$service);
		//get admin server object
		$admin = Mage::getModel('deployment/server_admin');
		$output .= $admin->setOutputRenderer($outputRenderer)->executeService($serviceObject, $paramObject);
		//get web servers object
		$ips = Mage::getStoreConfig('balance_deployment/'.$service.'/ips');
		//@todo check ips
		if(strpos($ips, ';')){
			$ips = explode(';',$ips);
			foreach($ips as $ip){
				if(strlen($ip)){
					$web = Mage::getModel('deployment/server_web', $ip);
					$output .= $web->setOutputRenderer($outputRenderer)->executeService($serviceObject, $paramObject);
				}
			}
		}
		
		$this->getResponse()->setBody($output);
	}
}