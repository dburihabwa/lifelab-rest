<?php

namespace LifeLab\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Appointment
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ExclusionPolicy("all")
 */
class Appointment
{
  /**
   * @var integer
   * @Type ("integer")
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   * @Expose
   */
  private $id;


  /**
   * @Type ("LifeLab\RestBundle\Entity\MedicalFile")
   * @ORM\ManyToOne(targetEntity="MedicalFile")
   * @ORM\JoinColumn(name="medical_file_id", referencedColumnName="id", nullable=false)
   */
  private $medicalFile;

  /**
   * @Type ("LifeLab\RestBundle\Entity\Doctor")
   * @ORM\ManyToOne(targetEntity="Doctor")
   * @ORM\JoinColumn(name="doctor_id", referencedColumnName="id", nullable=false)
   * @Expose
   */
  private $doctor;

    /**
   * @var datetime
   * @Type ("DateTime")
   *
   * @ORM\Column(name="date", type="datetime")
   * @Expose
   */
  private $date;


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
   * Set medicalFile
   *
   * @param \LifeLab\RestBundle\Entity\MedicalFile $medicalFile
   * @return Appointment
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

    /**
     * Set doctor
     *
     * @param \LifeLab\RestBundle\Entity\Doctor $doctor
     * @return Appointment
     */
    public function setDoctor(\LifeLab\RestBundle\Entity\Doctor $doctor)
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
     * Set date
     *
     * @param \DateTime $date
     * @return Appointment
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
}
