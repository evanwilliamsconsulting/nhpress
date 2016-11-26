<?php
namespace Application\Model;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\AbstractResultSet as AbstractResultSet;
use Zend\Stdlib\ArrayObject as ArrayObject;

class Issues extends AbstractResultSet
{
     private $itemArray;
     protected $em;
     protected $obj;
     protected $log;

     public function __construct()
     {
		$this->obj = new ArrayObject();
    }
    public function setLog($log)
    {
	$this->log = $log;
    }
    public function setEntityManager($em)
    {
    	$this->em = $em;
    }
	public function getEntityManager()
	{
		return $this->em;
	}
	public function loadDataSource()
	{
		$em = $this->getEntityManager();
		$this->log->info(print_r($em,true));
		$repository = $em->getRepository('Application\Entity\Issue');
		$this->log->info(print_r($repository,true));
		//$issues = $em->getRepository('Application\Entity\Issue')->findAll();
		$issues = $repository->findAll();
		$this->log->info(print_r($issues,true));
		foreach	($issues as $issue)
		{
			$newArray = Array();
			$newArray["type"] = "issue";	
			$newArray["object"] = $issue;
			$this->log->info(print_r($newArray,true));
			$this->obj->append($newArray);
		}
	}
    public function getDataSource()
    {
	 	return $this->obj;
	 }
     public function getFieldCount()
	 {
	 	$it = $this->obj->getIterator();
	 	return $it->count();
	 }
     /** Iterator */
     public function next()
	 {
	 	$it = $this->obj->getIterator();
	    return $it->next();	
	 }
     public function key()
	 {
	 	$it = $this->obj->getIterator();
	 	return $it->key();
	 }
     public function current()
	 {
	 	$it = $this->obj->getIterator();
	 	return $it->current();
	 }
     public function valid()
	 {
	 	$it = $this->obj->getIterator();
	 	return $it->valid();
	 }
     public function rewind()
	 {
	 	$it = $this->obj->getIterator();
	 	return $it->rewind();
	 }
     /** countable */
     public function count()
	 {
	 	$it = $this->obj->getIterator();
		return $it->count();	 	
	 }
     /** get rows as array */
     public function toArray()
	 {
	   $it = $this->obj->getIterator();
	   return $it->getArrayCopy();	
	 }
}
?>
