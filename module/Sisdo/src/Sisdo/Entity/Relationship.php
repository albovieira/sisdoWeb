<?php

namespace Sisdo\Entity;

use Application\Custom\EntityAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * Relationship
 *
 * @ORM\Table(name="relationship")
 * @ORM\Entity
 */
class Relationship extends EntityAbstract
{
    /**
     * @var \Application\Entity\User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="institution_user_id", referencedColumnName="user_id")
     * })
     */
    private $institutionUserId;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_user_id", referencedColumnName="user_id")
     * })
     */
    private $personUserId;

    /**
     * @return \Application\Entity\User
     */
    public function getInstitutionUserId()
    {
        return $this->institutionUserId;
    }

    /**
     * @param \Application\Entity\User $institutionUserId
     */
    public function setInstitutionUserId($institutionUserId)
    {
        $this->institutionUserId = $institutionUserId;
    }

    /**
     * @return \Application\Entity\User
     */
    public function getPersonUserId()
    {
        return $this->personUserId;
    }

    /**
     * @param \Application\Entity\User $personUserId
     */
    public function setPersonUserId($personUserId)
    {
        $this->personUserId = $personUserId;
    }

}

