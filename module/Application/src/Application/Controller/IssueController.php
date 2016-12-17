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

use Application\Model\Issues as Issues;
use Application\Entity\Issue as Issue;
use Application\View\Helper\WordageHelper as WordageHelper;
use Application\Service\WordageService as WordageService;

use Application\View\Helper\PictureHelper as PictureHelper;
use Zend\Session\Container;

use Application\View\Helper\UserToolbar as UserToolbar;
use Application\View\Helper\Toolbar as Toolbar;

class IssueController extends AbstractActionController
{
	protected $em;
	protected $authservice;
	protected $username;
	protected $log;
	protected $obj;

	public function __construct()
	{
	}
	public function getEntityManager()
	{
		if (null == $this->em)
		{
		    try {
			$this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
			//$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		    } catch (Exception $e) {
			//print_r($e);
			//print_r($e-getPrevious());
		    }
		}
	return $this->em;
	}
	public function indexAction()
	{
		$this->log = $this->getServiceLocator()->get('log');
		$log = $this->log;
		$log->info("Issue Controller");
		$layout = $this->layout();

		$this->log->info('Issue Controller: index');
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

		$layout->setTemplate('layout/issue');

		$userToolbar = new UserToolbar();
		$userToolbar->setUsername($username);
		$this->layout()->layouttest = $userToolbar->showOutput($attempt);

		$log->info("Get Entity Manager");
		
		$em = $this->getEntityManager();
		$log->info("Got Entity Manager");
		

		$new = $this->params()->fromQuery('new');

	
		if (!is_null($new))
		{
			if ($new == "issue")
			{
				$log->info("issue");
				$todaysdate = date("d/m/Y",time());
				$log->info($todaysdate);
				$newIssue = new Issue();
				$newIssue->setDateofpublication($todaysdate);
				$newIssue->setToggledivtagson(1);
				$newIssue->setPriceofcopy(.25);
				$newIssue->setTagline("A Paper Of Discussion");
				$newIssue->setQrimage("H");
				$newIssue->setHeadingtheme("bbb");
				$newIssue->setSecondtheme("bbb");
				$newIssue->setBrace("bbb");
				$log->info(print_r($newIssue,true));	
				$em->persist($newIssue);
				$em->flush();

				return $this->redirect()->toRoute('issue');
			}
		}
	

			// This second layout look really should happen if logged in.

		$issues = new Issues();
		$issues->setLog($log);
		$issues->setEntityManager($em);
		$issues->loadDataSource();


			$log->info(print_r($issues,true));
			
		$view = new ViewModel();
		
	$view->issues=$issues;
	$issueArray = Array();

	foreach ($issues->toArray() as $num => $item)
	{
		$issueObject = $item["object"];
		$issueArray[] = $issueObject;
	}
	$view->issues = $issueArray;

	$toolbar = new Toolbar();
	$toolbar->setUserName($username);
	$view->toolbar = $toolbar->showOutput($attempt);

	return $view;
    	}
}
