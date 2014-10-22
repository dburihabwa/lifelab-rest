<?php

namespace LifeLab\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Prescription
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ExclusionPolicy("all")
 */
class Prescription
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Expose
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Doctor")
     * @ORM\JoinColumn(name="doctor_id", referencedColumnName="id", nullable=false)
     * @Expose
     */
    private $doctor;

    /**
     * @ORM\ManyToOne(targetEntity="MedicalFile")
     * @ORM\JoinColumn(name="medical_file_id", referencedColumnName="id", nullable=false)
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
     * Set date
     *
     * @param \DateTime $date
     * @return Prescription
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set doctor
     *
     * @param \LifeLab\RestBundle\Entity\Doctor $doctor
     * @return Prescription
     */
    public function setDoctor(\LifeLab\RestBundle\Entity\Doctor $doctor = null)
    {
        $this->doctor = $doctor;

        return $this;
    }

    /**
     * Get doctor
     *
     * @return \LifeLab\RestBundle\Entity\Doctor 
     */
    public function getDoctor()
    {
        return $this->doctor;
    }

    /**
     * Set medicalFile
     *
     * @param \LifeLab\RestBundle\Entity\MedicalFile $medicalFile
     * @return Prescription
     */
    public function setMedicalFile(\LifeLab\RestBundle\Entity\MedicalFile $medicalFile = null)
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
