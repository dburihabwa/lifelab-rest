<?php

namespace LifeLab\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * MedicalFile
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class MedicalFile
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
     * @ORM\OneToOne(targetEntity="Patient")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", nullable=false, unique=true)
     */
    private $patient;


    /**
     * @ORM\ManyToMany(targetEntity="Allergy")
     * @ORM\JoinTable(
     *                  name="allergies_in_medical_file",
     *                  joinColumns={@ORM\JoinColumn(name="medical_file_id", referencedColumnName="id")},
     *                  inverseJoinColumns={@ORM\JoinColumn(name="allergy_id", referencedColumnName="id")}
     *               )
     */
    private $allergies;
    
    /**
     * @ORM\ManyToMany(targetEntity="Illness")
     * @ORM\JoinTable(
     *                  name="illnesses_in_medical_file",
     *                  joinColumns={@ORM\JoinColumn(name="medical_file_id", referencedColumnName="id")},
     *                  inverseJoinColumns={@ORM\JoinColumn(name="illness_id", referencedColumnName="id")}
     *               )
     */
    private $illnesses;

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
     * Set patient
     *
     * @param \LifeLab\RestBundle\Entity\Patient $patient
     * @return MedicalFile
     */
    public function setPatient(\LifeLab\RestBundle\Entity\Patient $patient = null)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \LifeLab\RestBundle\Entity\Patient 
     */
    public function getPatient()
    {
        return $this->patient;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->allergies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add allergies
     *
     * @param \LifeLab\RestBundle\Entity\Allergy $allergies
     * @return MedicalFile
     */
    public function addAllergy(\LifeLab\RestBundle\Entity\Allergy $allergies)
    {
        $this->allergies[] = $allergies;

        return $this;
    }

    /**
     * Remove allergies
     *
     * @param \LifeLab\RestBundle\Entity\Allergy $allergies
     */
    public function removeAllergy(\LifeLab\RestBundle\Entity\Allergy $allergies)
    {
        $this->allergies->removeElement($allergies);
    }

    /**
     * Get allergies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAllergies()
    {
        return $this->allergies;
    }

    /**
     * Add illnesses
     *
     * @param \LifeLab\RestBundle\Entity\Illness $illnesses
     * @return MedicalFile
     */
    public function addIllness(\LifeLab\RestBundle\Entity\Illness $illnesses)
    {
        $this->illnesses[] = $illnesses;

        return $this;
    }

    /**
     * Remove illnesses
     *
     * @param \LifeLab\RestBundle\Entity\Illness $illnesses
     */
    public function removeIllness(\LifeLab\RestBundle\Entity\Illness $illnesses)
    {
        $this->illnesses->removeElement($illnesses);
    }

    /**
     * Get illnesses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIllnesses()
    {
        return $this->illnesses;
    }
}
