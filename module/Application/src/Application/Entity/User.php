<?php

namespace Application\Entity;

use Application\Custom\EntityAbstract;
use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User extends EntityAbstract implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=12, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=45, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", length=45, nullable=false)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="profile", type="integer", nullable=false)
     */
    private $profile;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=false)
     */
    private $email;


    /**
     * @ORM\OneToOne(targetEntity="Sisdo\Entity\Institution", mappedBy="userId")
     **/
    private $instituicao;

    /**
     * @ORM\OneToOne(targetEntity="Sisdo\Entity\Person", mappedBy="userId")
     **/
    private $person;

    /**
     * @ORM\OneToOne(targetEntity="Sisdo\Entity\Contact", mappedBy="userId")
     **/
    private $contact;

    /**
     * @ORM\OneToOne(targetEntity="Sisdo\Entity\Adress", mappedBy="userId")
     **/
    private $adress;

    /**
     * @return mixed
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * @param mixed $adress
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }



    /**
     * @return \Sisdo\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param \Sisdo\Entity\Person $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }

    /**
     * @return \Sisdo\Entity\Institution
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param \Sisdo\Entity\Institution $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }




    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->userId;
    }

    /**
     * Set id.
     *
     * @param int $id
     * @return UserInterface
     */
    public function setId($userId)
    {
        $this->userId = $userId;
    }


    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
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
     * @return int
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param int $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {

    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {

    }



    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        // TODO: Implement getState() method.
    }

    /**
     * Set state.
     *
     * @param int $state
     * @return UserInterface
     */
    public function setState($state)
    {
        // TODO: Implement setState() method.
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }




}
