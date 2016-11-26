<?php
//  http://framework.zend.com/manual/current/en/in-depth-guide/services-and-servicemanager.html

 namespace Application\Service;

 use Application\Model\Issue;

 interface IssueServiceInterface
 {
 	 /**
      * {@inheritDoc}
      */
     /**
      * Should return a set of all Issues that we can iterate over. Single entries of the array are supposed to be
      * implementing \Application\Model\Issue
      *
      * @return array|Items[]
      */
     public function findAllItems();

     /**
      * Should return a single Item
      *
      * @param  int $id Identifier of the Item that should be returned
      * @return Item
      */
     public function findItems($id);
 
}
