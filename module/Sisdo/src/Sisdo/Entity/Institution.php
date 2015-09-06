<?php

namespace Sisdo\Entity;

use Application\Custom\EntityAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * Institution
 *
 * @ORM\Table(name="institution", indexes={@ORM\Index(name="fk_institution_user_idx", columns={"user_id"})})
 * @ORM\Entity
 */
class Institution extends EntityAbstract
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
     * @ORM\Column(name="corporate_name", type="string", length=100, nullable=false)
     */
    private $corporateName;

    /**
     * @var string
     *
     * @ORM\Column(name="fancy_name", type="string", length=100, nullable=true)
     */
    private $fancyName;

    /**
     * @var string
     *
     * @ORM\Column(name="cnpj", type="string", length=14, nullable=false)
     */
    private $cnpj;

    /**
     * @var string
     *
     * @ORM\Column(name="branch", type="string", length=50, nullable=false)
     */
    private $branch;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="string", length=500, nullable=false)
     */
    private $about;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\User", inversedBy="institution")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     **/
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
    public function getCorporateName()
    {
        return $this->corporateName;
    }

    /**
     * @param string $corporateName
     */
    public function setCorporateName($corporateName)
    {
        $this->corporateName = $corporateName;
    }

    /**
     * @return string
     */
    public function getFancyName()
    {
        return $this->fancyName;
    }

    /**
     * @param string $fancyName
     */
    public function setFancyName($fancyName)
    {
        $this->fancyName = $fancyName;
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

    /**
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param string $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    /**
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param string $about
     */
    public function setAbout($about)
    {
        $this->about = $about;
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

