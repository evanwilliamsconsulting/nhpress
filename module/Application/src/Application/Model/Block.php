<?php
namespace Application\Model;

use Zend\Stdlib\ArrayObject as ArrayObject;

class Block
{
	protected $id;
	protected $name;
	protected $page;
    protected $em;
    protected $obj;
    protected $log;
	protected $containerObj;
	protected $totalWidth;
	protected $blocks;
	protected static $cumulativeWidth;

	function __construct($id)
	{
		$this->id = $id;
		$this->obj = new ArrayObject();
	}
	public function setBlocks($blocks)
	{
		$this->blocks = $blocks;
	}
	public function getBlocks()
	{
		return $this->blocks;
	}
	public function setLog($log)
	{
		$this->log = $log;
	}
	public function getLog()
	{
		return $this->log;
	}
	function getId()
	{
		return $this->id;
	}
	function loadDataSource()
	{
		$em = $this->em;
		$id = $this->id;
		$containerItems = $em->getRepository('Application\Entity\ContainerItem')->findById($id);
		foreach ($containerItems as $items)
		{
			$containerItem = Array();
			$containerItem['width'] = $items->getWidth();
			$containerItem['height'] = $items->getHeight();
			$containerItem['gluex'] = $items->getGluex();
			$containerItem['gluey'] = $items->getGluey();
			$containerItem['prevx'] = $items->getPrevx();
			$containerItem['prevy']= $items->getPrevy();
			$containerItem['resetx'] = $items->getResetx();
			$containerItem['resety'] = $items->getResety();
			$containerItem['drift'] = $items->getDrift();
			$containerItem['gravity'] = $items->getGravity();
			$containerItem['offsetx'] = $items->getOffsetx();
			$containerItem['offsety'] = $items->getOffsety();
			$typeId = $items->getContainerTypeId();
			$typeIdReference = $items->getContainertypeidref();
			$containerItem['reference']=$typeIdReference;

			$containerTypes = $em->getRepository('Application\Entity\ContainerType')->findById($typeId);
			$type = $containerTypes[0]->getContainerType();
			$containerItem["type"]=$type;

			// Now, given the Reference to an object, load that object
			if ($type == "wordage")
			{
				$contentObject = $em->getRepository('Application\Entity\Wordage')->findById($typeIdReference);
			}
			else if ($type == "picture")
			{
				$contentObject = $em->getRepository('Application\Entity\Picture')->findById($typeIdReference);
			}
			$containerItem["content"] = $contentObject[0];
			$this->obj->append($containerItem);
		}
	}
	public function setEntityManager($em)
    {
    	$this->em = $em;
    }
	public function getEntityManager()
	{
		return $this->em;
	}
	function setObject($obj)
	{
		$this->containerObj=$obj;
	}
	function setName($name)
	{
		$this->name = $name;
	}
	function getName()
	{
		return $this->name;
	}
	function setPage($page)
	{
		$this->page = $page;
	}
	function getPage()
	{
		return $this->page;
	}
	function getWidth()
	{
		$obj = $this->obj;
		$width = $obj[0]["width"];
		return $width;
	}
	function getHeight()
	{
		$obj = $this->obj;
		$height = $obj[0]["height"];
		return $height;
	}
	function toHTML()
	{
		$log = $this->getLog();
		$blocks = $this->getBlocks();
		$totalWidth = $blocks->getTotalWidth();
		$log->info($totalWidth);
		$obj = $this->obj;
		//$width = $obj[0]["width"];
		$height = $obj[0]["height"];
		$width = $blocks->getCurrentBlockWidth();
		if ($height == 100)
		{
			$height = 400;
		}
		$log->info($width);
		$html = "<div ";
		$html .= "style='position:relative;float:left;width:";
		$html .= $width;
		$html .= "px;height:400";
		$html .= "px;color:black;border-style:solid;border-color:black;border-width:2px;background-color:pink;'>";
		$html .= $this->name;
		$content= $obj[0]["content"];
		$type = $obj[0]["type"];
		$content->setOutputWidth($width);
		$content->setOutputHeight($height);
		$html .= $content->toHTML();
		$html .= "</div>";
		return $html;
	}
}

?>