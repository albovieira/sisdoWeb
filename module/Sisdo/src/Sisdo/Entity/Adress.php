<?php

namespace Sisdo\Entity;

use Application\Custom\EntityAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * Adress
 *
 * @ORM\Table(name="adress", indexes={@ORM\Index(name="fk_adress_user1_idx", columns={"user_id"})})
 * @ORM\Entity
 */
class Adress extends EntityAbstract
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
     * @var string
     *
     * @ORM\Column(name="street_type", type="string", length=7, nullable=true)
     */
    private $streetType;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=100, nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="neighborhood", type="string", length=100, nullable=true)
     */
    private $neighborhood;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=10, nullable=true)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="uf", type="string", length=2, nullable=true)
     */
    private $uf;

    /**
     * @var integer
     *
     * @ORM\Column(name="country", type="integer", nullable=true)
     */
    private $country;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

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
    public function getStreetType()
    {
        return $this->streetType;
    }

    /**
     * @param string $streetType
     */
    public function setStreetType($streetType)
    {
        $this->streetType = $streetType;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getNeighborhood()
    {
        return $this->neighborhood;
    }

    /**
     * @param string $neighborhood
     */
    public function setNeighborhood($neighborhood)
    {
        $this->neighborhood = $neighborhood;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param string $uf
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
    }

    /**
     * @return int
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param int $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return \Application\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Application\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}

