<?php

/**
 * Created by PhpStorm.
 * User: BASIS-BH
 * Date: 30/04/2015
 * Time: 17:45.
 */

namespace Application\Dao;

use Application\Constants\UsuarioConst;
use Application\Custom\DaoAbstract;
use Application\Custom\Entity\BaseEntityAbstract;
use Doctrine\ORM\EntityManager;
use ZfcUser\Mapper\UserInterface;
use ZfcUserDoctrineORM\Options\ModuleOptions;


/**
 * Class ZfcUsuario
 * Classe responsavel por concentrar os metodos de acesso aos dados do compnente ZfcUser
 * @package Application\Dao
 */
class ZfcUsuario extends DaoAbstract implements UserInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \ZfcUserDoctrineORM\Options\ModuleOptions
     */
    protected $options;

    /**
     * @param EntityManager $em
     * @param ModuleOptions $options
     */
    public function __construct(EntityManager $em, ModuleOptions $options)
    {
        $this->em = $em;
        $this->options = $options;
    }

    /**
     * Retorna quantidade de falhas de conexao
     *
     * @param $userId
     *
     * @return int
     */
    public function getAccessAttempts($userId)
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        $user = $er->find($userId);

        return (int)$user->getQtdFalhasAutenticacao();
    }

    /**
     * Registra nova falha na autenticacao
     *
     * @param $userId
     *
     * @return mixed
     */
    public function addAccessAttempts($userId)
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        $user = $er->find($userId);
        $user->setQtdFalhasAutenticacao((int)($user->getQtdFalhasAutenticacao() < 10 ? $user->getQtdFalhasAutenticacao() : 8) + 1);

        return $this->update($user);
    }

    /**
     * Remove quantidade de falhas na autenticacao
     *
     * @param $userId
     *
     * @return mixed
     */
    public function resetAccessAttempts($userId)
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        $user = $er->find($userId);
      //  $user->setQtdFalhasAutenticacao(0);

        return $this->update($user);
    }

    /**
     * Regista ultimo acesso do usuario
     *
     * @param $userId
     *
     * @return mixed
     */
    public function setLastAccess($userId)
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());
        $user = $er->find($userId);
        //$user->setDataUltimoAcesso(Carbon::now());

        return $this->update($user);
    }

    /**
     * Retorna usuario a partor do hash
     *
     * @param $hash
     *
     * @return null|object
     */
    public function findByHash($hash)
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());

        return $er->findOneBy(array('codHash' => $hash));
    }

    /**
     * Retorna usuario a partir do username
     *
     * @param $username
     *
     * @return null|object
     */
    public function findByUsername($username)
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());

        return $er->findOneBy(array(UsuarioConst::FLD_LOGIN => $username));
    }

    /**
     * Retorna usuario a partir do email
     *
     * @param $email
     *
     * @return null|object
     */
    public function findByEmail($email)
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());

        return $er->findOneBy(array(UsuarioConst::FLD_EMAIL => $email));
    }

    /**
     * Retorna usuario a partir do id
     *
     * @param $id
     *
     * @return null|object
     */
    public function findById($id)
    {
        $er = $this->em->getRepository($this->options->getUserEntityClass());

        return $er->find($id);
    }

    /**
     * Atualização da entidade
     *
     * @param BaseEntityAbstract $entity
     * @return $this
     */
    public function refresh(BaseEntityAbstract $entity)
    {
        $this->em->refresh($entity);

        return $this;
    }

    /**
     * Persiste o registro de novo usuario
     *
     * @param Usuario $entity
     *
     * @return mixed
     */
    public function insert($entity)
    {
        $entity->setDate(new \DateTime());

        return $this->persist($entity);
    }

    /**
     * Atualiza a entidade Usuario no modelo
     *
     * @param Usuario $entity
     *
     * @return mixed
     */
    public function update($entity)
    {
        return $this->persist($entity);
    }

    /**
     * Persiste entidade
     *
     * @param Usuario $entity
     *
     * @return Usuario
     */
    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush($entity);

        return $entity;
    }
}
