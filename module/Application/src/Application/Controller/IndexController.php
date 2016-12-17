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
use Hex\View\Helper\CustomHelper;
use Application\Form\Panel\LoginForm;
use Zend\EventManager\EventManger;
use Publish\BlockHelper as BlockHelper;
use Publish\Block\Broadsheet;
use Zend\Session\Container;
use Application\Active;
use Application\View\Helper\UserToolbar as UserToolbar;

use Application\Model\Items as Items;
use Application\Model\Blocks as Blocks;
use Application\Entity\Wordage as Wordage;

use Application\View\Helper\ContainerHelper as ContainerHelper;
use Application\Service\WordageService as WordageService;

use Application\View\Helper\PictureHelper as PictureHelper;
//use Zend\Session\Container;

//use Application\View\Helper\UserToolbar as UserToolbar;



class IndexController extends AbstractActionController
{
	private $windowWidth;
	private $windowHeight;
	
    public function boilerplateAction()
    {
    	$log = $this->getServiceLocator()->get('log');
    	$log->info('Will work equally well');
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
        return $this->redirect()->toRoute('correspondant');
	}
	$log->info($username);
	$log->info($attempt);
	$userToolbar = new UserToolbar();
	$userToolbar->setUserName($username);
	$this->layout()->layouttest = $userToolbar->showOutput($attempt);
	$log->info("Got View Model");
	$this->layout()->content = $this->content();
	/*
				$year = '2015';
				$month = '12';
				$day = '15';
				$pageno = 1;
				$broadsheet = new Broadsheet($year,$month,$day,$pageno);
	    //$broadsheet = new Broadsheet("http://nhpress.net/index_html/pageone/");
	    $broadsheet->refresh();
	    $log->info("New Block Helper");
	    $log->info("set Base URI");
	    $snapshot = $broadsheet->toHTML();
	    $log->info("snapshot");


	    $view->content = print_r($snapshot,true); 
	*/
	
	    $log->info("content");
	    
		/*
		 * On window resize you are to call the resize event.
		 * Do not PUSH window size out!
		 *
		$view->width = $this->windowWidth;
		$view->height = $this->windowHeight;
		$view->style = "background-color:red;width:";
		$view->style .= $this->width;
		$view->style .= "px;height:100px;";
		 * 
		 */
    	$this->getServiceLocator()->get('log')->info("Hi");
        //return $view;
    }

    public function indexAction()
    {
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

    	$this->log = $this->getServiceLocator()->get('log');
        $log = $this->log;
        $log->info("Presentation Controller");

        $em = $this->getEntityManager();

        $layout = $this->layout();
    	$layout->setTemplate('layout/presentation');

        $blocks = new Blocks();
        $blocks->setTotalWidth(874);
        $blocks->setLog($log);
        $blocks->setEntityManager($em);
        $blocks->loadDataSource();

	    $view  = new ViewModel();  

	    $blockArray = Array();

/* 2DO:
	What you see below is incomplete code.
	What I am trying to do is to make
	This controller operate like the Correspondant Controller.
	The Correspondant Controller properly takes each type that is returned by
	The Data fetch, and returns an object for that type as well as
	its associated View Helper.

	That said, this functionality should be inside of the Blocks object,
	and as for the Correspondant controller, this type of functionality
	should not be in the controller itself but in its corresponding Items object.

	And That said, this design pattern should be encapsulated into a pattern with a class and object and instantiation method
	Because the next thing is that we will want a Graphic Editor with Graphic Elements that we can drag and drop or use in a paid advertizement. $$

	That said, the Blocks Object retrieves Types but its data elements right now are one kind, namely Containers.
	The Items Object on the other hand retrieves the Wordages and Pictures.
	The Containers contain references to these Wordages and Pictures so that we don't duplicate content even in the database.
	The Containers also contain Headlines Captions and other Accessories.
	Regardless, once we retrieve a Container, we have to have some indication as to the type so that we can fetch the appropriate Helper
	by the Block object itself, just like the Item object can choose to be helped by WordageHelper or PictureHelper,
	although this, as we mentioned, currently happens in the Correspondant Controller.

	One thing we do not want to do is to create a ContainerHelper: it would be of no use because although it would represent the container
	it would again need to decide if we have wordage or picture or other: the problem becomes recursive in the programming structure and is not solved.

	Likewise we also do not want to create a different sort of helper for each type of Layout.  A Container can perhaps contain one or two headlines or other things as Container Items; it can be continued onto other pages.  We want the simplest solution that will allow us to create layout and .PDF from this structure without a lot more objects, such as an entire templating system, involved.
	

	*/
    
	foreach ($blocks->toArray() as $num => $block)
	{
		// Note that a Container is all that is and should be required in an accurate model of the Newspaper
		// Layout engine to place items, that is, ContainerItems on a page.
        
        	$container = $block["block"];
        	$id = $container->getId();
        	$name = $container->getName();
		    $page = $container->getPage();
        	$bcolor = '#ff22bb';
        	
            $view = new ViewModel(array('object' => $container,
        		'id' => $id,
        		'name' => $name,
        		'bcolor' => $bcolor
        	));
        
		// The Container Item is not differentiated in anyway by the Items it contains on the page at this time.
		// There are enough placement clues within the items in the container to layout anything that you might want
		// Nonetheless it would be helpful to have specific Helpers for specific repeated layout elements
		// And motifs, such as ArticleHelper, FeatureHelper, PictureHelper, even though these are not
		// represented that way in the Objects structure.

		// We might be able to use a Dictionary and some Clues to determine what kind of Layout Placement type we have
		// This would greatly improve things when we try to switch output devices; i.e. build the PDF.
            
        	$containerItem = new ContainerHelper();
        	
            $containerItem->setServiceLocator($this->getServiceLocator());
        	
            $containerItem->setViewModel($view);
        	$containerItem->setContainerObject($block["block"]);

        	$blockArray[] = $containerItem;
	}
	
/*
        $blocks->setTotalWidth(900);


	   $output = $blocks->toHTML();

       $view->blocks = $output;
*/
       $view->blocks = $blockArray;
	   //$view->blocks = print_r($blockArray,true);

       // $view->blocks = "<div>BLOCK</div>";
       // $view->blocks = print_r($blocks,true);
       //$view->blocks = print_r($blocks->toArray(),true);

       return $view;
    }

	public function loginAction()
	{
		$view = new ViewModel();
		$view->setTerminal(true);
		$form = new LoginForm();
		$boxHTML = "<div>TEST</div>";
		$view->box = $form;
		return $view;
	}
/*
	public function setsizeAction()
	{
		/
		 * This is correct code for receiving parameters from an ajax call
		 * But not the way to go about setting the window sizes.
		 * Need to use window.resize and percentages in layout.
		 *
		$view = new ViewModel();
		$view->setTerminal(true);
		$result = $_POST;
		$this->windowWidth = $result['width'];
		$this->windowHeight = $result['height'];
		$view->data = "success";
		return $view;
	}
*/
    public function welcomeAction()
    {
        $view = new ViewModel();

        $view->content = $this->content();
        
        return $view;
    }
    public function content()
    {
	return "content";
    }
    public function getEntityManager()
    {
        if (null == $this->em)
        {
	    try {
            	$this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                //$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            } catch (Exception $e) {
		print_r($e);
		print_r($e-getPrevious());
	       }
    	}
	   return $this->em;
    }

}
