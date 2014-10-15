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
     * Constructor
     */
    public function __construct()
    {
        $this->allergies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->illnesses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->treatments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->prescriptions = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add treatments
     *
     * @param \LifeLab\RestBundle\Entity\Treatment $treatments
     * @return MedicalFile
     */
    public function addTreatment(\LifeLab\RestBundle\Entity\Treatment $treatments)
    {
        $this->treatments[] = $treatments;

        return $this;
    }

    /**
     * Remove treatments
     *
     * @param \LifeLab\RestBundle\Entity\Treatment $treatments
     */
    public function removeTreatment(\LifeLab\RestBundle\Entity\Treatment $treatments)
    {
        $this->treatments->removeElement($treatments);
    }

    /**
     * Get treatments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTreatments()
    {
        return $this->treatments;
    }

    /**
     * Add prescriptions
     *
     * @param \LifeLab\RestBundle\Entity\Prescription $prescriptions
     * @return MedicalFile
     */
    public function addPrescription(\LifeLab\RestBundle\Entity\Prescription $prescriptions)
    {
        $this->prescriptions[] = $prescriptions;

        return $this;
    }

    /**
     * Remove prescriptions
     *
     * @param \LifeLab\RestBundle\Entity\Prescription $prescriptions
     */
    public function removePrescription(\LifeLab\RestBundle\Entity\Prescription $prescriptions)
    {
        $this->prescriptions->removeElement($prescriptions);
    }

    /**
     * Get prescriptions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrescriptions()
    {
        return $this->prescriptions;
    }
}
