<?php

/**
 * Created by PhpStorm.
 * User: BASIS-BH
 * Date: 20/04/2015
 * Time: 10:17.
 */

namespace Application\Custom;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class EntityAbstract.
 */
abstract class EntityAbstract extends Entity\BaseEntityAbstract
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $version;

    /**
     * @var
     */
    public static $repository;

    /**
     * Retorna o repositorio da entidad
     *
     * @param \Doctrine\ORM\EntityManager $em
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public static function repository(\Doctrine\ORM\EntityManager $em)
    {
        return $em->getRepository(get_called_class());
    }

    /**
     * Inicilaiza um repositorio para a entidade
     *
     * @InjectParams({
     *     "em" = @Inject("doctrine.orm.entity_manager")
     * })
     */
    public function setInitialStatus(\Doctrine\ORM\EntityManager $em)
    {
        self::$repository = $em->getRepository('AcmeSampleBundle:User');
        //...
    }
}
