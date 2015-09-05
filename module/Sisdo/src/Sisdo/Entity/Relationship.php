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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


}

