<?php
namespace Application\Service;

use Application\Model\Issue as Issue;

class IssueService implements IssueServiceInterface
{
     /**
      * {@inheritDoc}
      */
    protected $sm;
	protected $em;
	 
    public function __construct($sm)
	{
		$this->sm = $sm;
	}
    public function getEntityManager()
    {
        if (null == $this->em)
        {
            $this->em = $this->sm->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    }
	    return $this->em;
    }
     public function findAllItems()
     {
     	$this->em = $this->getEntityManager();
         // TODO: Implement findAllItems() method.
        $issue = new Issue($em);
        //$em = $this->getEntityManager();
		
		//$wordage= $em->getRepository('Application\Entity\Wordage')->findAll();
		
		//return $wordage;
		return $issue->toArray();

     /**
      * {@inheritDoc}
      */
     public function findIssue($id)
     {
         // TODO: Implement findItems() method.
     }
}
