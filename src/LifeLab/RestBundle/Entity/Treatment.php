<?php

namespace LifeLab\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Treatment
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ExclusionPolicy("all")
 */
class Treatment
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
     * @var datetime
     * @Type ("DateTime")
     *
     * @ORM\Column(name="date", type="datetimetz")
     * @Expose
     */
    private $date;

    /**
     * @var integer
     * @Type ("integer")
     *
     * @ORM\Column(name="frequency", type="integer")
     * @Expose
     */
    private $frequency;

    /**
     * @var float
     * @Type ("float")
     * @ORM\Column(name="quantity", type="float")
     * @Expose
     */
    private $quantity;

    /**
     * @Type ("LifeLab\RestBundle\Entity\Medicine")
     * @ORM\ManyToOne(targetEntity="Medicine")
     * @ORM\JoinColumn(name="medicine_id", referencedColumnName="id", nullable=false)
     * @Expose
     */
    private $medicine;

    /**
     * @Type ("LifeLab\RestBundle\Entity\Prescription")
     * @ORM\ManyToOne(targetEntity="Prescription")
     * @ORM\JoinColumn(name="prescription_id", referencedColumnName="id", nullable=true)
     * @Expose
     */
    private $prescription;

    /**
     * @var integer
     * @Type("integer")
     * @ORM\Column(name="duration", type = "integer")
     * @Expose
     */
    private $duration;
    
    /**
     * @Type ("LifeLab\RestBundle\Entity\MedicalFile")
     * @ORM\ManyToOne(targetEntity="MedicalFile")
     * @ORM\JoinColumn(name="medical_file_id", referencedColumnName="id", nullable=false)
     */
    private $medicalFile;

    /**
     * Builds and returns a list of intakes matching covering the duration of the treatment.
     * @return LifeLab\RestBundle\Entity\Intake[] An array of intakes covering the duration of the treatment 
     */
    public function computeExpectedIntakes() {
        $intakes = array();        
        $date = clone $this->date;
        $duration = new \DateInterval('P' . $this->duration . 'D');
        $endDate = clone $this->date;
        $endDate = $endDate->add($duration);
        $timeInterval = new \DateInterval('PT' . $this->frequency . 'H');
        while ($date < $endDate) {
            $key = $date->format('c');
            $intake = new Intake();
            $intake->setTreatment($this);
            $intake->setTime(clone $date);
            $intakes[$key] = $intake;
            $date = $date->add($timeInterval);
        }
        return $intakes;
    }

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
     * @return Treatment
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
     * Set frequency
     *
     * @param integer $frequency
     * @return Treatment
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get frequency
     *
     * @return integer 
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * Set quantity
     *
     * @param float $quantity
     * @return Treatment
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return float 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Treatment
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set medicine
     *
     * @param \LifeLab\RestBundle\Entity\Medicine $medicine
     * @return Treatment
     */
    public function setMedicine(\LifeLab\RestBundle\Entity\Medicine $medicine)
    {
        $this->medicine = $medicine;

        return $this;
    }

    /**
     * Get medicine
     *
     * @return \LifeLab\RestBundle\Entity\Medicine 
     */
    public function getMedicine()
    {
        return $this->medicine;
    }

    /**
     * Set prescription
     *
     * @param \LifeLab\RestBundle\Entity\Prescription $prescription
     * @return Treatment
     */
    public function setPrescription(\LifeLab\RestBundle\Entity\Prescription $prescription = null)
    {
        $this->prescription = $prescription;

        return $this;
    }

    /**
     * Get prescription
     *
     * @return \LifeLab\RestBundle\Entity\Prescription 
     */
    public function getPrescription()
    {
        return $this->prescription;
    }

    /**
     * Set medicalFile
     *
     * @param \LifeLab\RestBundle\Entity\MedicalFile $medicalFile
     * @return Treatment
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
