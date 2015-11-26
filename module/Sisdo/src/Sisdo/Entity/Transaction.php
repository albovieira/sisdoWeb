<?php

namespace Sisdo\Entity;
use Application\Custom\EntityAbstract;
use Doctrine\Common\Collections\ArrayCollection;
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
    protected $id;

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
     * @ORM\Column(name="status", type="string", length=1, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="shipping_method", type="string", length=1, nullable=true)
     */
    private $shippingMethod;

    /**
     * @var \Sisdo\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="Sisdo\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="institution_user_id", referencedColumnName="user_id")
     * })
     */
    private $institutionUser;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_user_id", referencedColumnName="user_id")
     * })
     */
    private $personUser;


    /**
     * @ORM\OneToMany(targetEntity="Sisdo\Entity\Message",cascade="all", mappedBy="idTransacao")
     **/
    private $messages;

    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="string", nullable=true)
     */
    private $observacao;

    /**
     * Transaction constructor.
     * @param $messages
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }


    /**
     * @return ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add Message
     *
     * @param \Sisdo\Entity\Message $messages
     * @return Message
     */
    public function addMessages(ArrayCollection $messages)
    {
        /** @var Message $inspecao */
        foreach ($messages as $message) {
            $this->addMessage($message);
        }

        return $this;
    }

    /**
     * Add inspecao
     *
     * @param \Sisdo\Entity\Message $message
     * @return Message
     */
    public function addMessage(\Sisdo\Entity\Message $message)
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
        }
        return $this;
    }

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
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
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
     * @return \Sisdo\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param \Sisdo\Entity\Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return \Application\Entity\User
     */
    public function getInstitutionUser()
    {
        return $this->institutionUser;
    }

    /**
     * @param \Application\Entity\User $institutionUser
     */
    public function setInstitutionUser($institutionUser)
    {
        $this->institutionUser = $institutionUser;
    }

    /**
     * @return \Application\Entity\User
     */
    public function getPersonUser()
    {
        return $this->personUser;
    }

    /**
     * @param \Application\Entity\User $personUser
     */
    public function setPersonUser($personUser)
    {
        $this->personUser = $personUser;
    }

    /**
     * @return string
     */
    public function getShippingMethod()
    {
        return $this->shippingMethod;
    }

    /**
     * @param string $shippingMethod
     */
    public function setShippingMethod($shippingMethod)
    {
        $this->shippingMethod = $shippingMethod;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }




}

