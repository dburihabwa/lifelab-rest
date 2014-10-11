<?php

namespace LifeLab\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="dosage", type="string")
     */
    private $dosage;

    /**
     * @var integer
     *
     * @ORM\Column(name="dangerous", type="integer")
     */
    private $dangerous;

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
     * Set dosage
     *
     * @param string $dosage
     * @return Medicine
     */
    public function setDosage($dosage)
    {
        $this->dosage = $dosage;

        return $this;
    }

    /**
     * Get dosage
     *
     * @return string 
     */
    public function getDosage()
    {
        return $this->dosage;
    }

    /**
     * Set dangerous
     *
     * @param integer $dangerous
     * @return Medicine
     */
    public function setDangerous($dangerous)
    {
        $this->dangerous = $dangerous;

        return $this;
    }

    /**
     * Get dangerous
     *
     * @return integer 
     */
    public function getDangerous()
    {
        return $this->dangerous;
    }
}
