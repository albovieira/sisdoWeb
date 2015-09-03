<?php

/**
 * Created by PhpStorm.
 * User: BASIS-BH
 * Date: 22/04/2015
 * Time: 14:55.
 */

namespace Application\Custom;

use Application\Entity\Usuario;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DaoAbstract.
 */
abstract class DaoAbstract extends Dao\BaseDaoAbstract
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param $serviceLocator
     */
    public function __construct(\Doctrine\ORM\EntityManager $entityManager, $serviceLocator)
    {
        $this->setEntityManager($entityManager);
        $this->setServiceLocator($serviceLocator);
        $this->getDQLCustomFunction();

        if (!$this->entityName) {
            throw new \RuntimeException("A propriedade 'entityName' não foi definida ou esta incorreta no DAO " . get_called_class() . "\nModelo:  protected \$entityName = '\\\\Module\\\\Entity\\\\EntityName';");
        }
        $arr = explode('\\', $this->entityName);
        $this->alias = strtolower(substr(end($arr), 0, 1));
    }

    /**
     * Funções personalizadas do Oracle para o Doctrine.
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function getDQLCustomFunction()
    {
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomDatetimeFunction('trunc', '\Application\Custom\DqlFunctions\Trunc');
        $config->addCustomDatetimeFunction('to_char', '\Application\Custom\DqlFunctions\ToChar');
        $config->addCustomDatetimeFunction('int', '\Application\Custom\DqlFunctions\CastAsInteger');
        $config->addCustomDatetimeFunction('replace', '\Application\Custom\DqlFunctions\Replace');
    }

    /**
     * Retorna o Dao da entity informada.
     *
     * @param string $entityName
     *
     * @return DaoAbstract
     */
    protected function getDao($entityName)
    {
        $dao = $entityName . 'Dao';

        return $this->getServiceLocator()->get($dao);
    }

    /**
     * Set service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    private function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator.
     *
     * @return ServiceLocatorInterface
     */
    private function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Return a field Name with a Alias table name
     *
     * @param $fieldName
     * @return string
     */
    public function getName($fieldName)
    {
        return $this->getAlias() . '.' . $fieldName;
    }
}
