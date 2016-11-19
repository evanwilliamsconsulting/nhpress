<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;
use Zend\ServiceManager\ServiceLocatorAwareInterface;  
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;
//use Application\Service\ContainerService as ContainerService;  
 
class ContainerHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected static $state;
	protected $containerObject;
	protected $container;
	protected $username;
	protected $itemId;
	protected $viewmodel;
	protected $renderer;

    /** 
     * Set the service locator. 
     * 
     * @ param ServiceLocatorInterface $serviceLocator 
     * @ return CustomHelper 
     */ 
    public function __construct()
	{
	}
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)  
    {  
        $this->serviceLocator = $serviceLocator;  
        return $this;  
    } 
    /** 
     * Get the service locator. 
     * 
     * @ return \Zend\ServiceManager\ServiceLocatorInterface 
     */  
    public function getServiceLocator()  
    {  
        return $this->serviceLocator;  
    }  
    public function setViewModel(ViewModel $viewmodel)
	{
		$this->viewmodel = $viewmodel;
	}
	public function getViewModel()
	{
		return $this->viewmodel;
	}
	public function setContainerObject($containerObject)
	{
		$this->containerObject = $containerObject;
		$this->container = $containerObject->getId();
	}
	public function toHTML()
	{
		return $this->containerObject->toHTML();
	}
	public function setUsername($username)
	{
		$this->username = $username;
	}
	public function setItemId($itemId)
	{
		$this->itemId = $itemId;
	}
	public function setRenderer($renderer)
	{
		$this->renderer = $renderer;
	}
	public function getRenderer()
	{
		return $this->renderer;
	}
    public function __invoke()
    {
        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
    	//$sm = $this->getServiceLocator()->getServiceLocator();  
        //$config = $sm->get('application')->getConfig(); 
 
        //$retval = "<div>";
	//	$retval .= $this->containerObject->getWordage();
	//	$retval .= "</div>";
    	
    	$view = $this->getViewModel();

    	$view->html = $this->toHTML();
		
		$view->setTemplate('items/container.phtml');
		
		//return $view;
		
		//return $retval;
		
//		return print_r($view,true);
	    return $viewRender->render($view);
    }
}
