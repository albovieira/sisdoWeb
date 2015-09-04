<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Custom\Dao\BaseDaoAbstract;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        //Pega o serviço translator definido no arquivo module.config.php (aliases)
        $translator = $e->getApplication ()->getServiceManager ()->get ( 'translator' );

        //Define o local onde se encontra o arquivo de tradução de mensagens
        $translator->addTranslationFile ( 'phpArray', './vendor/zendframework/zendframework/resources/languages/pt_BR/Zend_Validate.php' );

        //Define o local (você também pode definir diretamente no método acima
        $translator->setLocale ( 'pt_BR' );

        //Define a tradução padrão do Validator
        AbstractValidator::setDefaultTranslator ( new Translator( $translator ) );

        $em = $eventManager->getSharedManager();

        $em->attach(
            'ZfcUser\Form\Register',
            'init',
            function($e)
            {
                /* @var $form \ZfcUser\Form\Register */
                $form = $e->getTarget();

                $form->remove('email');
                $form->get('username')->setLabel('Login');
                $form->get('password')->setLabel('Senha');
                $form->get('passwordVerify')->setLabel('Confirmar Senha');

            }
        );

        // here's the storage bit

        $zfcServiceEvents = $e->getApplication()->getServiceManager()->get('zfcuser_user_service')->getEventManager();

        $zfcServiceEvents->attach('register', function($e) {
            $form = $e->getParam('form');
            $user = $e->getParam('user');
            /* @var $user \Application\Entity\User */
            $user->setUsername(  $form->get('username')->getValue() );
        });

        // you can even do stuff after it stores
      //  $zfcServiceEvents->attach('register.post', function($e) {
            /*$user = $e->getParam('user');*/
       // });

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
