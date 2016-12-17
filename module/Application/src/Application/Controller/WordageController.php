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

use Doctrine\ORM\EntityManager;
use Application\Form\Entity\CorrespondantForm;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

use Zend\Stdlib\ArrayObject as ArrayObject;

use Application\Model\Items as Items;
use Application\Entity\Wordage as Wordage;

use Application\View\Helper\WordageHelper as WordageHelper;
use Application\Service\WordageService as WordageService;

use Application\View\Helper\PictureHelper as PictureHelper;
use Zend\Session\Container;

use Application\View\Helper\UserToolbar as UserToolbar;
use Application\View\Helper\Toolbar as Toolbar;

class WordageController extends AbstractActionController
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
	$this->log = $this->getServiceLocator()->get('log');
        $log = $this->log;
        $log->info("Wordage Controller");

		$this->log = $this->getServiceLocator()->get('log');
		$this->log->info('Correspondant Controller: index');
   		$userSession = new Container('user');
		if (!isset($userSession->test))
		{
			$attempt = "notloggedin"; 
			$username = "notloggedin";
			$this->log->info("Not Logged In!");
			return $this->redirect()->toRoute('/');
		}
		else
		{
			$attempt = $userSession->test;
			$username = $userSession->username;
			$this->log->info("Logged In! Username: " . $username);
		}
		$userToolbar = new UserToolbar();
		$userToolbar->setUserName($username);
		$this->layout()->layouttest = $userToolbar->showOutput($attempt);


	$log->info("Get Entity Manager");
        
	$em = $this->getEntityManager();
	
	$log->info("Got Entity Manager");

/*
	$new = $this->params()->fromQuery('new');

	if (!is_null($new))
	{
		if ($new == "wordage")
		{
			$log->info("wordage");
			$todaysdate = date("d/m/Y",time());
			$log->info($todaysdate);
			$newWordage = new Wordage();
			$newWordage->setOriginal($todaysdate);
			$newWordage->setTitle("new");
			$newWordage->setUsername("evanwill");
			$newWordage->setWordage("new");
			$newWordage->setColumnsize("65");
		        $log->info(print_r($newWordage,true));	
			$em->persist($newWordage);
			$em->flush();

			return $this->redirect()->toRoute('correspondant');
		}
	}
*/

	$layout = $this->layout();
	// This second layout look really should happen if logged in.
	$layout->setTemplate('layout/correspondant');

	$log->info("Set Layout");



	$items = new Items();
	$items->setEntityManager($em);
	$items->loadDataSource();

	$log->info("New Items");
	$log->info(print_r($items,true));

	
		
	$view = new ViewModel();

	$log->info("New ViewModel");
		
	$itemArray = Array();


	foreach ($items->toArray() as $num => $item)
	{
		if ($item["type"] == "Wordage")
		{
			$wordageObject = $item["object"];
			$wordage = $wordageObject->getWordage();
			$id = $wordageObject->getId();
			$original = $wordageObject->getOriginal();
			$title = $wordageObject->getTitle();
			//$username = $wordageObject->getUsername();
			$bcolor = '#ff22bb';
			$view = new ViewModel(array('wordage' => $wordage,
				'id' => $id,
				'original' => $original,
				'title' => $title,
				'username' => $username,
				'bcolor' => $bcolor
			));
			$wordageItem = new WordageHelper();
			$wordageItem->setServiceLocator($this->getServiceLocator());
			$wordageItem->setViewModel($view);
			$wordageItem->setUsername($username);
			$wordageItem->setEntityManager($this->getEntityManager());
			$wordageItem->setWordageObject($item["object"]);
			$itemArray[] = $wordageItem;
		}
/*
		else 
		{
			$pictureObject = $item["object"];
			$picture = $pictureObject->getPicture();
			$id = $pictureObject->getId();
			$original = $pictureObject->getOriginal();
			$caption = $pictureObject->getCaption();
			//$username = $pictureObject->getUsername();
			$bcolor = '#00bbbb';
			$view = new ViewModel(array('picture' => $picture,
				'id' => $id,
				'original' => $original,
				'caption' => $caption,
				'username' => $username,
				'bcolor' => $bcolor
			));
			$pictureItem = new PictureHelper();
			$pictureItem->setServiceLocator($this->getServiceLocator());
			$pictureItem->setViewModel($view);
			$pictureItem->setUsername($username);
			$pictureItem->setPictureObject($item["object"]);
			$itemArray[] = $pictureItem;
		}		
*/
	}


	$log->info(print_r($view->items,true));
	$log->info("Set Items");
	$view->items = $itemArray;


	$log->info("new Toolbar");
	$toolbar = new Toolbar();
	$toolbar->setUserName($username);
	$view->toolbar = $toolbar->showOutput($attempt);
	$log->info("return view");


        return $view;

    }
    public function fetchAction()
    {
	$this->log = $this->getServiceLocator()->get('log');
        $log = $this->log;
        $log->info("Wordage Controller");

	$this->log = $this->getServiceLocator()->get('log');
	$this->log->info('Correspondant Controller: index');
   	$userSession = new Container('user');
	if (!isset($userSession->test))
	{
		$attempt = "notloggedin"; 
		$username = "notloggedin";
		$this->log->info("Not Logged In!");
		return $this->redirect()->toRoute('/');
	}
	else
	{
		$attempt = $userSession->test;
		$username = $userSession->username;
		$this->log->info("Logged In! Username: " . $username);
	}
	$userToolbar = new UserToolbar();
	$userToolbar->setUserName($username);
	$this->layout()->layouttest = $userToolbar->showOutput($attempt);

	$log->info("Get Entity Manager");
        
	$em = $this->getEntityManager();
	
	$log->info("Got Entity Manager");

	$layout = $this->layout();
	// This second layout look really should happen if logged in.
	$layout->setTemplate('layout/correspondant');

	$log->info("Set Layout");

	$view = new ViewModel();

	$log->info("New ViewModel");

	$adapter = new \Zend\Db\Adapter\Adapter( array(
		'driver' => 'Pdo_Mysql',
		'database' => 'nhp',
		'username' => 'root',
		'password' => 'ptH3984z',
		'hostname' => 'localhost',
		'charset' => 'utf8'
	));

	$sql = "select * from class_ids where class_id='who-is-an-expert';";

	$stmt = $adapter->query($sql);
	$results = $stmt->execute();
	
	$view->results = print_r($results->current(),true);

	$currentRow = $results->current();

	$zoid = $currentRow['zoid'];

	$log->info("Zoid");
	$log->info($zoid);

	$sql2 = "select state from object_state where zoid=" . $zoid . ";";

	$stmt2 = $adapter->query($sql2);
	$results2 = $stmt2->execute();

	$currentState = $results2->current();

	$state = $currentState['state'];
	$log->info("state");
	$log->info($state);

	//$phpzope = new PHPZope();

	$log->info("new Toolbar");
	$toolbar = new Toolbar();
	$toolbar->setUserName($username);
	$view->toolbar = $toolbar->showOutput($attempt);
	$log->info("return view");

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
	$log->info("wordage delete action");

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
	$userToolbar = new UserToolbar();
	$userToolbar->setUserName($username);
	$this->layout()->layouttest = $userToolbar->showOutput($attempt);

	// Retrieve Wordage Id that was passed in and perform delete!
	$wordageid = $this->params()->fromQuery('id');
	$log->info($wordageid);
	// Looking for: wordage- or 8 chars
	$theId = substr($wordageid,strpos('-',$wordageid)+8,strlen($wordageid));
	$theArray = array('id' => $theId);
	$em = $this->getEntityManager();
	$wordage = $em->getRepository('Application\Entity\Wordage')->findOneBy($theArray);
	$em->remove($wordage);
	$em->flush();

	// Return JSON okay!
	$variables = array("id" => $wordageid,"result" => "ok");
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
    	$log->info("wordage edit action");

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
		$userToolbar = new UserToolbar();
		$userToolbar->setUserName($username);
		$this->layout()->layouttest = $userToolbar->showOutput($attempt);
    	
		$viewModel = new ViewModel();
		$viewModel->setTemplate("edit");
		$renderer = new PhpRenderer();
		$resolver = new Resolver\AggregateResolver();
		$renderer->setResolver($resolver);

		$map = new Resolver\TemplateMapResolver(array(
    		'edit'      => __DIR__ . '/../../../view/application/wordage/edit.phtml',
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
		$layout->setTemplate('layout/wordage');


		$wordageid = $this->params()->fromQuery('id');
		$log->info($wordageid);
		// Looking for: wordage- or 8 chars
		$theId = substr($wordageid,strpos('-',$wordageid)+8,strlen($wordageid));
		$viewModel->setVariable('theid',$theId);

		$theArray = array('id' => $theId);

		$em = $this->getEntityManager();
		$wordage = $em->getRepository('Application\Entity\Wordage')->findOneBy($theArray);
		$actualWords = $wordage->getWordage();
		$viewModel->setVariable('actualWords',$actualWords);
		$viewModel->setVariable('id',$wordageid);

		$variables = array("id" => $wordageid,"view" => $renderer->render($viewModel),"thewordage" => print_r($wordage,true));
		$jsonModel = new JsonModel($variables);
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent(json_encode($variables));
		
		return $response;
    }
    public function updateAction()
    {
    	$this->log = $this->getServiceLocator()->get('log');
    	$log = $this->log;
    	$log->info("Wordage Update Action");

		$variables = array("status" => "200");
		$jsonModel = new JsonModel($variables);
		$content = $this->getRequest()->getContent();
		//$content = $this->params()->fromPost('data');
		$log->info($content);

		$wordageid = $this->params()->fromQuery('id');
		$log->info($wordageid);
		$theId = substr($wordageid,strpos('wordage-',$wordageid)+8,strlen($wordageid));
		$theArray = array('id' => $theId);

		$log->info($theId);

		$em = $this->getEntityManager();
		$wordage = $em->getRepository('Application\Entity\Wordage')->findOneBy($theArray);

		$wordage->setWordage($content);
		$em->persist($wordage);
		$em->flush();

        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent(json_encode($variables));
		return $response;
    }
}
