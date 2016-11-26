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
 * @ORM\Table(name="issue")
 */
class Issue
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dateofpublication", type="string", length=255, nullable=false)
     */
    private $dateofpublication;

    /**
     * @var boolean
     *
     * @ORM\Column(name="toggledivtagson", type="boolean", nullable=false)
     */
    private $toggledivtagson;

    /**
     * @var string
     *
     * @ORM\Column(name="priceofcopy", type="string", length=255, nullable=false)
     */
    private $priceofcopy;

    /**
     * @var string
     *
     * @ORM\Column(name="tagline", type="string", length=255, nullable=false)
     */
    private $tagline;

    /**
     * @var string
     *
     * @ORM\Column(name="qrimage", type="string", length=255, nullable=false)
     */
    private $qrimage;

    /**
     * @var string
     *
     * @ORM\Column(name="headingtheme", type="string", length=255, nullable=false)
     */
    private $headingtheme;

    /**
     * @var string
     *
     * @ORM\Column(name="secondtheme", type="string", length=255, nullable=false)
     */
    private $secondtheme;

    /**
     * @var string
     *
     * @ORM\Column(name="brace", type="string", length=255, nullable=false)
     */
    private $brace;


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
     * Set dateofpublication
     *
     * @param \DateTime $dateofpublication
     * @return Issue
     */
    public function setDateofpublication($dateofpublication)
    {
        $this->dateofpublication = $dateofpublication;

        return $this;
    }

    /**
     * Get dateofpublication
     *
     * @return \DateTime 
     */
    public function getDateofpublication()
    {
        return $this->dateofpublication;
    }

    /**
     * Set toggledivtagson
     *
     * @param boolean $toggledivtagson
     * @return Issue
     */
    public function setToggledivtagson($toggledivtagson)
    {
        $this->toggledivtagson = $toggledivtagson;

        return $this;
    }

    /**
     * Get toggledivtagson
     *
     * @return boolean 
     */
    public function getToggledivtagson()
    {
        return $this->toggledivtagson;
    }

    /**
     * Set priceofcopy
     *
     * @param string $priceofcopy
     * @return Issue
     */
    public function setPriceofcopy($priceofcopy)
    {
        $this->priceofcopy = $priceofcopy;

        return $this;
    }

    /**
     * Get priceofcopy
     *
     * @return string 
     */
    public function getPriceofcopy()
    {
        return $this->priceofcopy;
    }

    /**
     * Set tagline
     *
     * @param string $tagline
     * @return Issue
     */
    public function setTagline($tagline)
    {
        $this->tagline = $tagline;

        return $this;
    }

    /**
     * Get tagline
     *
     * @return string 
     */
    public function getTagline()
    {
        return $this->tagline;
    }

    /**
     * Set qrimage
     *
     * @param string $qrimage
     * @return Issue
     */
    public function setQrimage($qrimage)
    {
        $this->qrimage = $qrimage;

        return $this;
    }

    /**
     * Get qrimage
     *
     * @return string 
     */
    public function getQrimage()
    {
        return $this->qrimage;
    }

    /**
     * Set headingtheme
     *
     * @param string $headingtheme
     * @return Issue
     */
    public function setHeadingtheme($headingtheme)
    {
        $this->headingtheme = $headingtheme;

        return $this;
    }

    /**
     * Get headingtheme
     *
     * @return string 
     */
    public function getHeadingtheme()
    {
        return $this->headingtheme;
    }

    /**
     * Set secondtheme
     *
     * @param string $secondtheme
     * @return Issue
     */
    public function setSecondtheme($secondtheme)
    {
        $this->secondtheme = $secondtheme;

        return $this;
    }

    /**
     * Get secondtheme
     *
     * @return string 
     */
    public function getSecondtheme()
    {
        return $this->secondtheme;
    }

    /**
     * Set brace
     *
     * @param string $brace
     * @return Issue
     */
    public function setBrace($brace)
    {
        $this->brace = $brace;

        return $this;
    }

    /**
     * Get brace
     *
     * @return string 
     */
    public function getBrace()
    {
        return $this->brace;
    }
}
