<?php

namespace Sisdo\Entity;

use Application\Custom\EntityAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * MoneyDonation
 *
 * @ORM\Table(name="money_donation", indexes={@ORM\Index(name="id_instituition_user", columns={"id_instituition_user"}), @ORM\Index(name="id_person_user", columns={"id_person_user"})})
 * @ORM\Entity
 */
class MoneyDonation extends EntityAbstract
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1, nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_instituition_user", type="integer", nullable=false)
     */
    private $idInstituitionUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_person_user", type="integer", nullable=false)
     */
    private $idPersonUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime", nullable=false)
     */
    private $startdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime", nullable=false)
     */
    private $enddate;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getIdInstituitionUser()
    {
        return $this->idInstituitionUser;
    }

    /**
     * @param int $idInstituitionUser
     */
    public function setIdInstituitionUser($idInstituitionUser)
    {
        $this->idInstituitionUser = $idInstituitionUser;
    }

    /**
     * @return int
     */
    public function getIdPersonUser()
    {
        return $this->idPersonUser;
    }

    /**
     * @param int $idPersonUser
     */
    public function setIdPersonUser($idPersonUser)
    {
        $this->idPersonUser = $idPersonUser;
    }

    /**
     * @return \DateTime
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * @param \DateTime $startdate
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;
    }

    /**
     * @return \DateTime
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * @param \DateTime $enddate
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;
    }

    
}

