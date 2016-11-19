<?php
namespace Application\Model;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\AbstractResultSet as AbstractResultSet;
use Zend\Stdlib\ArrayObject as ArrayObject;

class Blocks extends AbstractResultSet
{
    private $blockArray;
    protected $em;
    protected $obj;
    protected $log;
    protected $totalWidth;
    protected $currentBlockWidth;

    public function __construct()
    {
    	$this->currentBlockWidth = 400;
    	$this->totalWidth = 880;
    	$this->cumulativeWidth = 0;
		$this->obj = new ArrayObject();
    }
    public function getCurrentBlockWidth()
    {
    	if ($this->totalWidth - $this->cumulativeWidth - $this->currentBlockWidth > 100)
    	{
    		$width = $this->currentBlockWidth;
    		$this->cumulativeWidth += $this->currentBlockWidth;
    		$this->totalWidth -= $currentBlockWidth;
    	}
    	else
    	{
    		$width = $this->totalWidth - $this->cumulativeWidth;
    	}
    	return $width;	
    }
    public function setEntityManager($em)
    {
    	$this->em = $em;
    }
	public function getEntityManager()
	{
		return $this->em;
	}
	public function setLog($log)
	{
		$this->log = $log;
	}
	public function increaseCumulativeWidth($additionalWidth)
	{
		$this->cumulativeWidth += $additionalWidth;
	}
	public function setCumulativeWidth($width)
	{
		$this->cumulativeWidth=$width;
	}
	public function getCumulativeWidth()
	{
		return $this->cumulativeWidth;
	}
	public function setTotalWidth($width)
	{
		$this->totalWidth = $width;
	}
	public function getTotalWidth($width)
	{
		return $this->totalWidth;
	}
	public function loadDataSource()
	{ 
        $log = $this->log;
        $log->info("Blocks - loadDataSource");

        $this->setCumulativeWidth(0);

		$em = $this->getEntityManager();
		$containers = $em->getRepository('Application\Entity\Container')->findAll();
		foreach	($containers as $container)
		{
			$newArray = Array();

			//$newArray["type"] = "Container";	
			//$newArray["object"] = $container;
			$name = $container->getName();
			$id = $container->getId();
			$page = $container->getPage();


			$block = new Block($id);
			$block->setBlocks($this);
			$block->setLog($log);
			$block->setName($name);
			$block->setObject($container);
			$block->setPage($page);
			$block->setEntityManager($em);
			$block->loadDataSource();
			
			$newArray["block"] = $block;

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
     public function getContainer()
	{
		return "Hello";
	}
     public function getId()
	{
		return "1234";
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
	 /*
	 public function toHTML()
	 {
	 	
	 	right = left + width
		bottom = top + height
		#if left >= pL and left <= pR:
		#	hOverlap = True
		#if right <= pR and right >= pL:
		#	hOverlap = True
		#if top >= pT and top <= pB:
		#	vOverlap = True
		#if bottom <= pB and bottom >= pT:
		#	vOverlap = True
		#if hOverlap and vOverlap:
		#	Overlap = True
		#if Overlap == True:
		#       |-------------------|
		#	|                   |
		#       |           |-----------|
		#	|	    |       |   |
                #       |           |       |   |
		#       |-----------|-------|---|
		#                   |           |
		#                   |-----------|
		#	additionalY = height - (bB - top)
		#	additionalX = width - (bR - left)
		#else:
			# There is no overlap.
			# That's great!
			# Determine for each dimension
			# if new box is above or below or left or right
			# prior to recalculating bounding box.
			
			# First vertical.
			# if height + top is greater than the previous bounding bottom
			# then new box is below old bounding box.
		    
			# If we are doing all this do we also need isOverlap
			# Yes but maybe not to calculate as above!
		
		# Here is where we jammd the this block
		thereWasNoFit = True
		bT = pT
		bL = pR 
		bW = width
		bH = height
		bB = bT + bH + 10
		bR = bL + bW + 10
		dL = bL
		testNumberOfColumns = numberOfColumns
		for space in oldFreeSpace:
			oT = space[0]
			oL = space[1]
			oH = space[2]
			oW = space[3]
			dW = oW
			if width <= oW and height <= oH:
				thereWasNoFit = False
				bT = oT
				bL = oL
				bW = oW
				bH = height
				dL = oL
				bB = bT + bH + 10
				bR = bL + bW + 10

		if thereWasNoFit == True:
			for space in oldFreeSpace:
				oT = space[0]
				oL = space[1]
				oH = space[2]
				oW = space[3]
				dW = oW
		
	 }
	 */
	 public function toHTML()
	 {
	 	$output = "<div style='position:relative;top:100px;left:100px;'>";
	 	$top = 0;
	 	$left = 0;
	 	$maxHeight = 0;
	 	$totalWidth = $this->totalWidth;

	 	foreach ($this->toArray() as $key => $block)
      	{
      		$width = $block->getWidth();
      		$height = $block->getHeight();

      		if ($height < $maxHeight)
      		{
      			$height = $maxHeight;
      		}
      		else
      		{
      			$maxHeight = $height;
      		}

      		if ($left + $width > $totalWidth)
      		{
      			$left = 0;
      			$top += $maxHeight;
      		}
      		else
      		{
      			$left += $width;
      		}


      		$output .= "<div style='position:absolute;top:";
      		$output .= $top;
      		$output .= "px;left:";
      		$output .= $left;
      		$output .= "px;'>";
        	$html = $block->toHTML();
	    	$output .= $html;
			$output .= "</div>";
	 	}
	 	$output .= "</div>";
	 	return $output;
	 }
}
?>
