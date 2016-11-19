<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Entity\Wordage;
use Hex\View\Helper\CustomHelper;
use Doctrine\ORM\EntityManager;
use Application\Form\Entity\WordageForm;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Session\Container;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

class PictureController extends AbstractActionController
{
    protected $em;
	protected $authservice;
	protected $username;
	protected $log;
 
    public function __construct()
	{
	}
    public function getEntityManager()
    {
        if (null == $this->em)
        {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	}
	return $this->em;
    }
	public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                                      ->get('AuthService');
        }
        return $this->authservice;
    }
    public function indexAction()
    {
    	$view = new ViewModel();
	    $view->content = $this->content();
		
		$layout = $this->layout();
		// This second layout look really should happen if logged in.
		$layout->setTemplate('layout/correspondant');
		
        return $view;
    }
    public function viewAction()
    {
    	// Load the logger
    	$this->log = $this->getServiceLocator()->get('log');
    	$log = $this->log;
    	$log->info("view action");
		
		// Initialize the View
    	$view = new ViewModel();
		// Retreive the parameters
		$id = $this->params()->fromRoute('item');
	    $log->info($id);
		
		// 2Do: Check to see that user is logged in
    	if (!$this->getAuthService()->hasIdentity())
        {
	       return $this->redirect()->toUrl('http://www.newhollandpress.com/');
        }
    	// 2Do: Populate username with user's username
    	$userSession = new Container('user');
		$this->username = $userSession->username;
		$log->info($this->username);
		
		$em = $this->getEntityManager();
		
		$wordage = $em->getRepository('Application\Entity\Wordage')->find($id);
		
		//$topic = new \Application\View\Helper\TopicToolbar('wordage');
		//$view->topic = $topic();
		$theWords = $wordage->getWordage();
		
		$view->content = $theWords;
		$view->id =$id;
        return $view;
    }
    public function content()
    {
	return "content";
    }
    public function wordageAction()
    {
	$view = new ViewModel();
        $view->content = $this->content();
        return $view;
    }
    public function newAction()
    {
	$view = new ViewModel();
        return $view;
    }
    public function changeAction()
    {
	$changedtext = $this->params()->fromPost('thetext');

	$wordageid = $this->params()->fromPost('id');
	$theId = substr($wordageid,strpos('wordage-',$wordageid)+8,strlen($wordageid));
	$theArray = array('id' => $theId);

	$em = $this->getEntityManager();
	$wordage = $em->getRepository('Application\Entity\Wordage')->findOneBy($theArray);

	$wordage->setWordage($changedtext);
	$em->persist($wordage);
	$em->flush();

	$variables = array("status" => "200",'id'=>$theId,'wordage'=>print_r($wordage,true));
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent(json_encode($variables));
	return $response;
    }
    public function deleteAction()
    {
		// Wordage Delete Action
		
		// Set up Log and announce that we are here!
	    	$this->log = $this->getServiceLocator()->get('log');
		$log = $this->log;
		$log->info("picture delete action");

		// Make sure we are logged in and retrieve user!
	   	$userSession = new Container('user');
		if (!isset($userSession->test))
		{
			$attempt = "notloggedin"; 
			$username = "notloggedin";
		}
		else
		{
			$attempt = $userSession->test;
			$username = $userSession->username;
		}

		// Retrieve Wordage Id that was passed in and perform delete!
		$pictureid = $this->params()->fromQuery('id');
		$log->info($pictureid);
		// Looking for: wordage- or 8 chars
		$theId = substr($pictureid,strpos('-',$pictureid)+8,strlen($pictureid));
		$theArray = array('id' => $theId);
		$em = $this->getEntityManager();
		$picture = $em->getRepository('Application\Entity\Picture')->findOneBy($theArray);
		$em->remove($picture);
		$em->flush();

		// Return JSON okay!
		$variables = array("id" => $pictureid,"result" => "ok");
		$jsonModel = new JsonModel($variables);
	    $response = $this->getResponse();
	    $response->setStatusCode(200);
	    $response->setContent(json_encode($variables));
		return $response;
    }
    public function editAction()
    {
   	$this->log = $this->getServiceLocator()->get('log');
   	$log = $this->log;
   	$log->info("view action");
	$view = new ViewModel();
	$view->setTemplate("edit");


	$renderer = new PhpRenderer();
	$resolver = new Resolver\AggregateResolver();
	$renderer->setResolver($resolver);

	$map = new Resolver\TemplateMapResolver(array(
    		'edit'      => __DIR__ . '/../../../view/application/picture/edit.phtml',
	));
	$stack = new Resolver\TemplatePathStack(array(
    		'script_paths' => array(
        	__DIR__ . '/view',
    		)
	));

	$resolver->attach($map);
	$resolver->attach($stack);


	$layout = $this->layout();
	// This second layout look really should happen if logged in.
	$layout->setTemplate('layout/picture');
	
	$pictureid = $this->params()->fromQuery('id');
	// Looking for: picture- or 8 chars
	$theId = substr($pictureid,strpos('-',$pictureid)+8,strlen($pictureid));
	$view->setVariable('theid',$theId);

	$theArray = array('id' => $theId);

	$em = $this->getEntityManager();
	$picture = $em->getRepository('Application\Entity\Picture')->findOneBy($theArray);
	$actualPicture  = $picture->getPicture();
	$fullPicturePath="/uploads/ewilliams/pix/" . $actualPicture;
	$view->setVariable('actualPicture',$fullPicturePath);
	$view->setVariable('id',$theId);

	$log->info($actualPicture);


	$variables = array("id" => $pictureid,"view" => $renderer->render($view),"thepicture" => print_r($picture,true));
	$jsonModel = new JsonModel($variables);
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent(json_encode($variables));
	return $response;
    }
}
