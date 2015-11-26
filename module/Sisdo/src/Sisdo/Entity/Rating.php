<?php

namespace Sisdo\Entity;

use Application\Custom\EntityAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Table(name="rating", indexes={@ORM\Index(name="fk_transaction_rating_idx", columns={"id_transacao"}), @ORM\Index(name="fk_person_user_rating_idx", columns={"person_user"}), @ORM\Index(name="fk_institution_user_rating_idx", columns={"institution_user"})})
 * @ORM\Entity
 */
class Rating extends EntityAbstract
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
     * @ORM\Column(name="rating", type="float", precision=10, scale=0, nullable=false)
     */
    private $rating;

    /**
     * @var \Sisdo\Entity\Transaction
     *
     * @ORM\OneToOne(targetEntity="Sisdo\Entity\Transaction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_transacao", referencedColumnName="id")
     * })
     */
    private $transacao;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_user", referencedColumnName="user_id")
     * })
     */
    private $personUser;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="institution_user", referencedColumnName="user_id")
     * })
     */
    private $institutionUser;

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
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param float $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return \Application\
     */
    public function getTransacao()
    {
        return $this->transacao;
    }

    /**
     * @param \Application\ $transacao
     */
    public function setTransacao($transacao)
    {
        $this->transacao = $transacao;
    }

    /**
     * @return \Application\
     */
    public function getPersonUser()
    {
        return $this->personUser;
    }

    /**
     * @param \Application\ $personUser
     */
    public function setPersonUser($personUser)
    {
        $this->personUser = $personUser;
    }

    /**
     * @return \Application\
     */
    public function getInstitutionUser()
    {
        return $this->institutionUser;
    }

    /**
     * @param \Application\ $institutionUser
     */
    public function setInstitutionUser($institutionUser)
    {
        $this->institutionUser = $institutionUser;
    }

}

