<?php

namespace LifeLab\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Patient
 *
 * @ORM\Table()
 * @ORM\Entity
 * 
 */
class Patient
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="MedicalFile")
     * @ORM\JoinColumn(name="medical_file_id", referencedColumnName="id", nullable=false, unique=true)
     */
    private $medicalFile;


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
     * @return Patient
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
     * Set medicalFile
     *
     * @param \LifeLab\RestBundle\Entity\MedicalFile $medicalFile
     * @return Patient
     */
    public function setMedicalFile(\LifeLab\RestBundle\Entity\MedicalFile $medicalFile)
    {
        $this->medicalFile = $medicalFile;

        return $this;
    }

    /**
     * Get medicalFile
     *
     * @return \LifeLab\RestBundle\Entity\MedicalFile
     */
    public function getMedicalFile()
    {
        return $this->medicalFile;
    }
}