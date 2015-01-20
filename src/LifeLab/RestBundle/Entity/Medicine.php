<?php

namespace LifeLab\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\Type;

/**
 * Medicine
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Medicine
{
    /**
     * @var integer
     * @Type ("integer")
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Type ("string")
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     * @Type ("string")
     *
     * @ORM\Column(name="shape", type="string")
     */
    private $shape;

    /**
     * @var string
     * @Type ("string")
     *
     * @ORM\Column(name="howToTake", type="string")
     */
    private $howToTake;


    /**
     * @var integer
     * @Type ("integer")
     *
     * @ORM\Column(name="dangerLevel", type="integer")
     */
    private $dangerLevel;


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
     * Set name
     *
     * @param string $name
     * @return Medicine
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set shape
     *
     * @param string $shape
     * @return Medicine
     */
    public function setShape($shape)
    {
        $this->shape = $shape;

        return $this;
    }

    /**
     * Get shape
     *
     * @return string 
     */
    public function getShape()
    {
        return $this->shape;
    }

    /**
     * Set howToTake
     *
     * @param string $howToTake
     * @return Medicine
     */
    public function setHowToTake($howToTake)
    {
        $this->howToTake = $howToTake;

        return $this;
    }

    /**
     * Get howToTake
     *
     * @return string 
     */
    public function getHowToTake()
    {
        return $this->howToTake;
    }

    /**
     * Set dangerLevel
     *
     * @param integer $dangerLevel
     * @return Medicine
     */
    public function setDangerLevel($dangerLevel)
    {
        $this->dangerLevel = $dangerLevel;

        return $this;
    }

    /**
     * Get dangerLevel
     *
     * @return integer 
     */
    public function getDangerLevel()
    {
        return $this->dangerLevel;
    }

    /**
     * Set application
     *
     * @param string $application
     * @return Medicine
     */
    public function setApplication($application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get application
     *
     * @return string 
     */
    public function getApplication()
    {
        return $this->application;
    }
}
