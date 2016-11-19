<?php

namespace Application\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ContainerType
 */
/**
 * @ORM\Entity
 * @ORM\Table(name="containertype")
 */
class ContainerType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="containertype", type="string", length=255, nullable=false)
     */
    private $containertype;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set containertype
     *
     * @param string $containertype
     *
     * @return ContainerType
     */
    public function setContainertype($containertype)
    {
        $this->containertype = $containertype;

        return $this;
    }

    /**
     * Get containertype
     *
     * @return string
     */
    public function getContainertype()
    {
        return $this->containertype;
    }
}

