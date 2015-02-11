<?php

namespace LifeLab\RestBundle\Entity;

use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Type;

use Doctrine\ORM\Mapping as ORM;

/**
 * Intake
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="unique_intake", columns={"treatment_id", "time"})})
 * @ORM\Entity
 */
class Intake
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
     * @Type ("DateTime")
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetimetz")
     */
    private $time;

    /**
     * @Exclude
     * @Type ("LifeLab\RestBundle\Entity\Treatment")
     * @ORM\ManyToOne(targetEntity="Treatment")
     * @ORM\JoinColumn(name="treatment_id", referencedColumnName="id", nullable=false)
     */
    private $treatment;

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
     * Set time
     *
     * @param \DateTime $time
     * @return Intake
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set treatment
     *
     * @param \LifeLab\RestBundle\Entity\Treatment $treatment
     * @return Intake
     */
    public function setTreatment(\LifeLab\RestBundle\Entity\Treatment $treatment)
    {
        $this->treatment = $treatment;

        return $this;
    }

    /**
     * Get treatment
     *
     * @return \LifeLab\RestBundle\Entity\Treatment 
     */
    public function getTreatment()
    {
        return $this->treatment;
    }
}
