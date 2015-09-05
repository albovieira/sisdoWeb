<?php

namespace Sisdo\Entity;
use Application\Custom\EntityAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction", indexes={@ORM\Index(name="fk_transaction_product1_idx", columns={"product_id"}), @ORM\Index(name="fk_transaction_institution1_idx", columns={"institution_user_id"}), @ORM\Index(name="fk_transaction_person1_idx", columns={"person_user_id"})})
 * @ORM\Entity
 */
class Transaction extends EntityAbstract
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity", type="string", length=45, nullable=true)
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    private $status;

    /**
     * @var \Application\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var \Application\Entity\Institution
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Institution")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="institution_user_id", referencedColumnName="user_id")
     * })
     */
    private $institutionUser;

    /**
     * @var \Application\Entity\Person
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Person")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_user_id", referencedColumnName="user_id")
     * })
     */
    private $personUser;


}

