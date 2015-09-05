<?php

/**
 * Created by PhpStorm.
 * User: BASIS-BH
 * Date: 20/05/2015
 * Time: 16:30.
 */

namespace Application\Service;

use Application\Constants\RotasConst;
use Application\Constants\SystemConst as sys;
use Application\Constants\UsuarioConst as Usuario;
use Application\Entity\Grupo;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Zend\View\Helper\Url;
use ZfcUser\Service\User;

/**
 * Class AutenticacaoService.
 */
class AutenticacaoService extends User
{
    /**
     *
     */
    const MAX_TENTATIVA_CHAVE_ACESSO = 3;

    /**
     * Gera um link com hash e envia para o email do usuario
     *
     * @param $emailUsuario
     *
     * @return bool
     */
    public function esqueciSenha($emailUsuario)
    {
        /** @var \Application\Entity\Usuario $usuario */
        $usuario = $this->getUserMapper()->findByEmail($emailUsuario);

        if (!$usuario) {
            return false;
        }

        $link = $usuario->getId();

        $this->getUserMapper()->update($usuario);

        /** @var Url $url */
        $url = $this->getServiceManager()->get('viewhelpermanager')->get('url');
        $link = $url(
            RotasConst::ALTERAR_SENHA,
            array(),
            array(
                'query' => array(
                    'userkey' => $link,
                ),
                'force_canonical' => true,
            )
        );

        $email = new EmailUtil($this->getServiceManager());
        $email->setSender(
            ReiniciarSenhaEmail::getNomeRemetente(),
            ReiniciarSenhaEmail::getRemetente()
        );
        $email->sendMail(
            $usuario->getNomUsuario(),
            $usuario->getEmail(),
            ReiniciarSenhaEmail::getAssunto(),
            ReiniciarSenhaEmail::getEmail(array('login' => $usuario->getUsername(), 'link' => $link))
        );

        return true;
    }


    /**
     * createFromForm
     *
     * @param array $data
     * @return \ZfcUser\Entity\UserInterface
     * @throws \ZfcUser\Service\Exception\InvalidArgumentException
     */
    public function register(array $data)
    {
        $class = $this->getOptions()->getUserEntityClass();
        $user = new $class;
        $form = $this->getRegisterForm();
        $form->setHydrator($this->getFormHydrator());
        $form->bind($user);
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }
        /** @var $user \ZfcUser\Entity\UserInterface */
        /** @var Usuario $user */
        $user = $form->getData();

        $em = $this->getFromServiceLocator(sys::ENTITY_MANAGER);
        $grupos = $form->get('grupos')->getValue();
        foreach ($grupos as $formGrupoId) {
            $user->addGrupo(Grupo::repository($em)->find($formGrupoId));
        }


        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->getOptions()->getPasswordCost());
        $user->setPassword($bcrypt->create($user->getPassword()));

        if ($this->getOptions()->getEnableUsername()) {
            $user->setUsername($data[Usuario::FLD_LOGIN]);
        }
        if ($this->getOptions()->getEnableDisplayName()) {
            //$user->setDisplayName($data[Usuario::FLD_NOME]);
        }

        // If user state is enabled, set the default state value
        if ($this->getOptions()->getEnableUserState()) {
            $user->setState($this->getOptions()->getDefaultUserState());
        }
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user, 'form' => $form));
        $this->getUserMapper()->insert($user);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'form' => $form));
        return $user;
    }

    /**
     * Altera a senha do usuário
     *
     * @param array $data
     *
     * @return bool
     */
    public function changePassword(array $data)
    {
        /** @var \Application\Entity\Usuario $currentUser */
        $currentUser = $this->getAuthService()->getIdentity();

        $oldPass = isset($data['credential']) ? $data['credential'] : null;
        $newPass = $data['newCredential'];

        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->getOptions()->getPasswordCost());

        if ($oldPass) {
            if (strcmp($oldPass, $newPass) == 0) {
                return false;
            }

            if (!$bcrypt->verify($oldPass, $currentUser->getPassword())) {
                return false;
            }

        } else {
            if ($bcrypt->verify($newPass, $currentUser->getPassword())) {
                return false;
            }
        }

        $pass = $bcrypt->create($newPass);
        $currentUser->setPassword($pass);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser, 'data' => $data));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $currentUser, 'data' => $data));

        return true;
    }

    /**
     * Verifica se a chave de acesso é valida
     *
     * @param array $data
     *
     * @return int
     */
    public function validaAcesso(array $data)
    {
        /** @var \Application\Entity\Usuario $user */
        $user = $this->getUserMapper()->findByUsername($data['identity']);

        $user->setDataUltimoAcesso(new \DateTime());
        $this->getUserMapper()->update($user);

        return Result::SUCCESS;
    }

    /**
     * Retorna o alias da entidade Usuario
     *
     * @return string
     */
    public function getTbAlias()
    {
        if (!$this->tbAlias) {
            $this->tbAlias = '?';
        }

        return $this->tbAlias;
    }

}
