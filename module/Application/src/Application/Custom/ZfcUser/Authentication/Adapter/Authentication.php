<?php

/**
 * Created by PhpStorm.
 * User: BASIS-BH
 * Date: 18/05/2015
 * Time: 17:13.
 */

namespace Application\Custom\ZfcUser\Authentication\Adapter;

use Application\Entity\Usuario;
use Application\Util\Mensagens;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container as SessionContainer;
use ZfcUser\Authentication\Adapter\AdapterChainEvent as AuthEvent;
use ZfcUser\Authentication\Adapter\Db;

/**
 * Class Authentication.
 */
class Authentication extends Db
{

    const MAX_DIAS_INATIVOS = 60;

    const MAX_DIAS_ALTERACAO_SENHA = 60;

    const MAX_TENTATIVA_ACESSO = 3;

    /**
     * Realiza a autenticacao a partir dos dados de acesso fornecidos
     *
     * @param AuthEvent $e
     *
     * @return bool
     */
    public function authenticate(AuthEvent $e)
    {
        if ($this->isSatisfied()) {
            $storage = $this->getStorage()->read();
            $e->setIdentity($storage['identity'])
                ->setCode(AuthenticationResult::SUCCESS)
                ->setMessages(array('Authentication successful.'));

            return true;
        }

        if ($e->getRequest()->getQuery()->get('userkey', null)) {
            return $this->authenticateByToken($e);
        }

        $identity = $e->getRequest()->getPost()->get('identity');
        $credential = $e->getRequest()->getPost()->get('senUsuario');
        $credential = $this->preProcessCredential($credential);

        /** @var Usuario $userObject */
        $userObject = null;

        // Cycle through the configured identity sources and test each
        $fields = $this->getOptions()->getAuthIdentityFields();
        while (!is_object($userObject) && count($fields)) {
            $mode = array_shift($fields);
            switch ($mode) {
                case 'username':
                    $userObject = $this->getMapper()->findByUsername($identity);
                    break;
                case 'email':
                    $userObject = $this->getMapper()->findByEmail($identity);
                    break;
                default:
                    break;
            }
        }

        $e->setCode(AuthenticationResult::SUCCESS)
            ->setMessages(array('Authentication successful.'));

        /*
         * (E1)    Autenticação Inválida
         */
        if (!$userObject) {
            $e->setCode(AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND)
                ->setMessages(array(Mensagens::getMensagem('M10')));
            $this->setSatisfied(false);

            return false;
        }

        /*
         * Valida se é a senha do usuario esta incorreta.
         * (E1)    Autenticação Inválida
         */
        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->getOptions()->getPasswordCost());
        if (!$bcrypt->verify($credential, $userObject->getPassword())) {

            // Password does not match
            $e->setCode(AuthenticationResult::FAILURE_CREDENTIAL_INVALID)
                ->setMessages(array(Mensagens::getMensagem('M10')));
            $this->setSatisfied(false);

            return false;
        }

        /*
         * (E2)    Valida se os Usuário Esta Sem Perfil Associado (por inexistência ou o gestor tirou seu acesso)
         */
        if ($userObject->getGrupos()->count() == 0) {
            $e->setCode(-6)
                ->setMessages(array(Mensagens::getMensagem('M11')))
                ->setIdentity($userObject);
            $this->setSatisfied(false);

            return false;
        }

        //$this->getMapper()->resetAccessAttempts($userObject->getId());
        //$this->getMapper()->setLastAccess($userObject->getId());

        // regen the id
        $session = new SessionContainer($this->getStorage()->getNameSpace());
        $session->getManager()->regenerateId();

        // Success!
        $e->setIdentity($userObject->getId());
        // Update user's password hash if the cost parameter has changed
        $this->updateUserPasswordHash($userObject, $credential, $bcrypt);
        $this->setSatisfied(true);

        $storage = $this->getStorage()->read();
        $storage['identity'] = $e->getIdentity();

        $this->getStorage()->write($storage);
    }

    /**
     * Realiza a autenticacao a partir do token de acesso
     *
     * @param AuthEvent $e
     *
     * @return bool
     */
    public function authenticateByToken(AuthEvent $e)
    {
        if ($this->isSatisfied()) {
            $storage = $this->getStorage()->read();
            $e->setIdentity($storage['identity'])
                ->setCode(AuthenticationResult::SUCCESS)
                ->setMessages(array('Authentication successful.'));

            return true;
        }

        $hash = $e->getRequest()->getQuery()->get('userkey');

        //voltar ao arrumar a gravação do hash
        //$userObject = $this->getMapper()->findByHash($hash);

        //remover
        $userObject = $this->getMapper()->findById($hash);

        if (!$userObject) {
            $e->setCode(AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND)
                ->setMessages(array(Mensagens::getMensagem('M05')));
            $this->setSatisfied(false);

            return false;
        }

        /* @var \Application\Entity\Usuario $userObject */
        /*$validDate = new Carbon($userObject->getDataValidadeHash());
        if ($validDate->diffInDays(Carbon::now(), false) > 0) {
            $e->setCode(AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND)
                ->setMessages(array(Mensagens::getMensagem('M05')));
            $this->setSatisfied(false);

            return false;
        }*/

        $this->getMapper()->resetAccessAttempts($userObject->getId());
        $this->getMapper()->setLastAccess($userObject->getId());

        // regen the id
        $session = new SessionContainer($this->getStorage()->getNameSpace());
        $session->getManager()->regenerateId();

        // Success!

        $e->setCode(AuthenticationResult::SUCCESS)
            ->setMessages(array('Authentication successful.'));
        $e->setIdentity($userObject->getId());
        $this->setSatisfied(true);

        $storage = $this->getStorage()->read();
        $storage['identity'] = $e->getIdentity();

        $this->getStorage()->write($storage);
    }
}
