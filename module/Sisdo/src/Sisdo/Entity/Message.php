<?php

namespace Sisdo\Entity;

use Application\Custom\EntityAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity
 */
class Message extends EntityAbstract
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
     * @ORM\ManyToOne(targetEntity="Sisdo\Entity\Transaction", inversedBy="transaction")
     * @ORM\JoinColumn(name="id_transacao", referencedColumnName="id")
     **/
    private $idTransacao;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="user_id")
     * })
     */
    private $idUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", nullable=true)
     */
    private $message;

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
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
     * @return int
     */
    public function getIdTransacao()
    {
        return $this->idTransacao;
    }

    /**
     * @param int $idTransacao
     */
    public function setIdTransacao($idTransacao)
    {
        $this->idTransacao = $idTransacao;
    }

    /**
     * @return string
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param string $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
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


}

