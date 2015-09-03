<?php

namespace GerenciaEstoque\Entity;

use Application\Custom\EntityAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * Fornecedor
 *
 * @ORM\Table(name="fornecedor")
 * @ORM\Entity
 */
class Fornecedor extends EntityAbstract
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_fornecedor", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFornecedor;

    /**
     * @var string
     *
     * @ORM\Column(name="nome_fornecedor", type="string", length=145, nullable=false)
     */
    private $nomeFornecedor;

    /**
     * @var string
     *
     * @ORM\Column(name="cnpj", type="string", length=14, nullable=false)
     */
    private $cnpj;

    /**
     * @return int
     */
    public function getIdFornecedor()
    {
        return $this->idFornecedor;
    }

    /**
     * @param int $idFornecedor
     */
    public function setIdFornecedor($idFornecedor)
    {
        $this->idFornecedor = $idFornecedor;
    }

    /**
     * @return string
     */
    public function getNomeFornecedor()
    {
        return $this->nomeFornecedor;
    }

    /**
     * @param string $nomeFornecedor
     */
    public function setNomeFornecedor($nomeFornecedor)
    {
        $this->nomeFornecedor = $nomeFornecedor;
    }

    /**
     * @return string
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * @param string $cnpj
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
    }


}

