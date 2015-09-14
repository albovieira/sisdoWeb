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


}

