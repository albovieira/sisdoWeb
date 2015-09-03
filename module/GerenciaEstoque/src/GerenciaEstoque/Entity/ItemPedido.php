<?php

namespace GerenciaEstoque\Entity;

use Application\Custom\EntityAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * ItemPedido
 *
 * @ORM\Table(name="item_pedido", indexes={@ORM\Index(name="fk_pedido_idx", columns={"id_pedido"}), @ORM\Index(name="fk_produto_idx", columns={"id_produto"})})
 * @ORM\Entity
 */
class ItemPedido extends EntityAbstract
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
     * @var float
     *
     * @ORM\Column(name="quantidade", type="float", precision=10, scale=0, nullable=false)
     */
    private $quantidade;

    /**
     * @var \GerenciaEstoque\Entity\Pedido
     *
     * @ORM\ManyToOne(targetEntity="GerenciaEstoque\Entity\Pedido")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_pedido", referencedColumnName="id_pedido")
     * })
     */
    private $idPedido;

    /**
     * @var \GerenciaEstoque\Entity\Produto
     *
     * @ORM\ManyToOne(targetEntity="GerenciaEstoque\Entity\Produto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_produto", referencedColumnName="id_produto")
     * })
     */
    private $idProduto;

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
     * @return float
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * @param float $quantidade
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    /**
     * @return \GerenciaEstoque\
     */
    public function getIdPedido()
    {
        return $this->idPedido;
    }

    /**
     * @param \GerenciaEstoque\ $idPedido
     */
    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;
    }

    /**
     * @return \GerenciaEstoque\
     */
    public function getIdProduto()
    {
        return $this->idProduto;
    }

    /**
     * @param \GerenciaEstoque\ $idProduto
     */
    public function setIdProduto($idProduto)
    {
        $this->idProduto = $idProduto;
    }


}

