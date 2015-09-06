<?php

namespace Sisdo\Entity;
use Application\Custom\EntityAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product", indexes={@ORM\Index(name="fk_product_institution1_idx", columns={"institution_user_id"}), @ORM\Index(name="fk_product_product_type1_idx", columns={"product_type_id"})})
 * @ORM\Entity
 */
class Product extends EntityAbstract
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProduto;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=45, nullable=false)
     */
    private $title;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float", nullable=false)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

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
     * @var \Sisdo\Entity\ProductType
     *
     * @ORM\ManyToOne(targetEntity="Sisdo\Entity\ProductType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_type_id", referencedColumnName="id")
     * })
     */
    private $productType;

    /**
     * @return int
     */
    public function getIdProduto()
    {
        return $this->idProduto;
    }

    /**
     * @param int $id
     */
    public function setIdProduto($idProduto)
    {
        $this->idProduto = $idProduto;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param string $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \Sisdo\Entity\Institution
     */
    public function getInstitutionUser()
    {
        return $this->institutionUser;
    }

    /**
     * @param \Sisdo\Entity\Institution $institutionUser
     */
    public function setInstitutionUser($institutionUser)
    {
        $this->institutionUser = $institutionUser;
    }

    /**
     * @return \Sisdo\Entity\ProductType
     */
    public function getProductType()
    {
        return $this->productType;
    }

    /**
     * @param \Sisdo\Entity\ProductType $productType
     */
    public function setProductType($productType)
    {
        $this->productType = $productType;
    }




}

