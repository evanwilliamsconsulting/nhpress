<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\Date;

/**
 * @ORM\Entity
 * @ORM\Table(name="wordage")
 */
class Wordage implements InputFilterAwareInterface
{
    private $columnsize;

    protected $em;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->original = (isset($data['original'])) ? $data['original'] : null;
        $this->title= (isset($data['title'])) ? $data['title'] : null;
        $this->wordage = (isset($data['wordage'])) ? $data['wordage'] : null;
        $this->columnSize = (isset($data['columnSize'])) ? $data['columnSize'] : null;
    }
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
			$factory = new InputFactory();

            $inputFilter->add(
            	$factory->createInput(array(
                'name' => 'id',
                'required' => false,
            )));

            $inputFilter->add(
            	$factory->createInput(array(
                'name' => 'username',
                'required' => false,
            )));
			

            $inputFilter->add(
            	$factory->createInput(array(
                'name' => 'original',
                'required' => false,
                'options' => array(
                	'format' => 'Ymd'
				)
            ))
			);

            $inputFilter->add(
            	$factory->createInput(array(
                'name' => 'title',
                'required' => false,
            )));

            $inputFilter->add(
            	$factory->createInput(array(
                'name' => 'wordage',
                'required' => false,
            )));

            $inputFilter->add(
            	$factory->createInput(array(
                'name' => 'columnSize',
                'required' => false,
            )));
 
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not Used");
    }
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="wordage", type="string", length=255, nullable=false)
     */
    private $wordage;

    /**
     *
	 * 
     * @ORM\Column(name="columnSize", type="integer", length=255, nullable=false)
     * @var integer
     */
    private $columnSize;
	
    /**
     * @var string
	 * 
	 * @ORM\Column(name="username", type="string", length=255, nullable=false)
     *
	 **/
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="original", type="string", length=255, nullable=false)
     */
    private $original;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", length=2255, nullable=false)
     */
    private $title;

    private $outputHeight;
    private $outputWidth;
    private $outputTitle;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Wordage
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set original_date
     *
     * @param \DateTime $original
     * @return Wordage
     */
    public function setOriginal($originalDate)
    {
        $this->original = $originalDate;

        return $this;
    }

    /**
     * Get original_date
     *
     * @return \DateTime 
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Wordage
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set wordage
     *
     * @param string $wordage
     * @return Wordage
     */
    public function setWordage($wordage)
    {
        $this->wordage = $wordage;

        return $this;
    }

    /**
     * Get wordage
     *
     * @return string 
     */
    public function getWordage()
    {
        return $this->wordage;
    }

    /**
     * Set columnsize
     *
     * @param integer $columnsize
     * @return Wordage
     */
    public function setColumnsize($columnsize)
    {
        $this->columnSize = $columnsize;

        return $this;
    }

    /**
     * Get columnsize
     *
     * @return integer 
     */
    public function getColumnsize()
    {
        return $this->columnsize;
    }
    public function setOutputHeight($height)
    {
        $this->outputHeight = $height;
    }
    public function getOutputHeight()
    {
        return $this->outputHeight;
    }
    public function setOutputWidth($width)
    {
        $this->outputWidth = $width;
    }
    public function getOutputWidth()
    {
        return $this->outputWidth;
    }
    public function setOutputTitle($title)
    {
        $this->outputTitle = $title;
    }
    public function getOutputTitle()
    {
        return $this->outputTitle;
    }
    public function toHTML()
    {
        $width = $this->getOutputWidth();
        $height = $this->getOutputHeight();
        $html = "<div style='width:";
        $html .= $width;
        $html .= "px;height:";
        $html .= $height;
        $html .= "px;background-color:green;color:black;border-style:dashed;border-width:2px;border-color:blue;'>";
        $title = $this->getOutputTitle();
        $html .= "<div style='width:";
        $html .= $width;
        $html .= "px;height:auto;color:white;background-color:black;border-style:dotted;border-width:2px;border-color:purple;'>";
        $html .= $this->wordage;
        $html .= "</div></div>";
        return $html;
    }
    public function getContainerItems()
    {
        // select * from container where id in (select id from containeritem
        //     where containertypeid=1 and containertypeidref=1

        // Wordage: containertypeid=1
        // Picture: containertypeid=2

        // This Wordage: containertypeidref=<this Wordage ID>

        // So get the ContainerItems that belong to this Wordage ID first.
        // Allow ContainerItems to return information about their parent containers.
        $em = $this->getEntityManager();

        $arrayQuery = Array();
        $wordageId = $this->getId();
        $arrayQuery["containertypeidref"] = $wordageId;
        $arrayQuery["containertypeid"] = 1;  

        $containerItems = $em->getRepository('Application\Entity\ContainerItem')->findBy($arrayQuery);

        // Need to Know Information about the Container that Contains Each 
        // ContainerItem in which this Wordage appears.
        $newArray = Array();


        foreach ($containerItems as $containerItem)
        {
            $newArray2 = Array();

            $id = $containerItem->getId();
            $containerId = $containerItem->getContainerId();
            $width = $containerItem->getWidth();
            $height = $containerItem->getHeight();

            $arrayQuery2 = Array();
            $arrayQuery["id"]=$containerId;

            $container = $em->getRepository('Application\Entity\Container')->findOneBy($arrayQuery2);

            $name = $container->getName();

            $newArray2['width'] = $width;
            $newArray2['height'] = $height;

            $newArray[$name] = $newArray2;
        }


        return $newArray;
    }
    public function setEntityManager($em)
    {
        $this->em = $em;
    }
    public function getEntityManager()
    {
        return $this->em;
    }

}
