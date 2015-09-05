<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Filter\EditarUsuarioFilter;
use Application\Filter\UsuarioFilter;
use Application\Form\UsuarioForm;
use Application\Util\Mensagens;
use ZfcUser\Validator\AbstractRecord;
use ZfcUser\Validator\NoRecordExists;

return array(
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                ),
            ),
        ),
    ),


    'controllers' => array(
        'factories' => array(
            'AutenticacaoController' => function ($controllerManager) {
                /* @var \Zend\Mvc\Controller\ControllerManager $controllerManager */
                $serviceManager = $controllerManager->getServiceLocator();

                /* @var \ZfcUser\Controller\RedirectCallback $redirectCallback */
                $redirectCallback = $serviceManager->get('zfcuser_redirect_callback');

                /* @var \Application\Controller\AutenticacaoController $controller */
                return new \Application\Controller\AutenticacaoController($redirectCallback);
            },


        ),

        'invokables' => array(
            'Application\Controller\Home' => 'Application\Controller\HomeController',
            'Application\Controller\Usuario' => 'Application\Controller\UsuarioController',
        ),
    ),

    'service_manager' => array(

        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'Zend\Session\ManagerInterface' => 'Zend\Session\Service\SessionManagerFactory',
            'Zend\Session\Config\ConfigInterface' => 'Zend\Session\Service\SessionConfigFactory',

            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',


            // Override ZfcUser User Mapper factory
            'zfcuser_user_mapper' => function ($sm) {
                return new \Application\Dao\ZfcUsuario($sm->get('zfcuser_doctrine_em'), $sm->get('zfcuser_module_options')
                );
            },
            /**
             * Override ZfcUser
             */

          'zfcuser_register_form' => function ($sm) {
                $options = $sm->get('zfcuser_module_options');
                $form = new UsuarioForm('usuarioForm', $options, $sm);
                $form->setInputFilter(new UsuarioFilter(
                    new NoRecordExists(array(
                        'mapper' => $sm->get('zfcuser_user_mapper'),
                        'key' => 'email',
                        'messages' => array(
                            AbstractRecord::ERROR_RECORD_FOUND => 'Email ja existe'
                        )
                    )),
                    new NoRecordExists(array(
                        'mapper' => $sm->get('zfcuser_user_mapper'),
                        'key' => 'username',
                        'messages' => array(
                            AbstractRecord::ERROR_RECORD_FOUND => 'login ja existe'
                        )
                    )),
                    $options
                ));

                return $form;
            },

           'user_edit_form' => function ($sm) {
               $options = $sm->get('zfcuser_module_options');
               $form = new UsuarioForm(null, $options, $sm);
               $filter = new EditarUsuarioFilter(
                   array(
                       'name' => 'StringLength',
                       'options' => array(
                           'min' => 1,
                       ),
                   ),
                   new NoRecordExists(array(
                       'mapper' => $sm->get('zfcuser_user_mapper'),
                       'key' => 'username',
                       'messages' => array(
                           AbstractRecord::ERROR_RECORD_FOUND => 'Login ja existe'
                       )
                   )),
                   $options);
               $form->setInputFilter($filter);
               return $form;
           },


//            'zfcRbac_RedirectStrategy' => 'Application\Custom\zfcRbac\Factory\RedirectStrategyFactory',

            'Zend\Mail\Transport\Smtp' => /**
             * @param $sm
             *
             * @return \Zend\Mail\Transport\Smtp
             */
                function ($sm) {
                    $config = $sm->get('config');
                    $smtpOptions = new \Zend\Mail\Transport\SmtpOptions($config['mail']);

                    return $smtp = new \Zend\Mail\Transport\Smtp($smtpOptions);
                },

            // Daos
            'UsuarioDao' => function ($sm) {
                return new \Application\Dao\UsuarioDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            // Services
            'UsuarioService' => function ($sm) {
                return new \Application\Service\UsuarioService($sm);
            },

        ),
        'invokables' => array(
            'zfcuser_user_service' => 'Application\Service\AutenticacaoService',
            'Custom\zfcUser\Authentication' => 'Application\Custom\ZfcUser\Authentication\Adapter\Authentication',
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/unauthorized' => __DIR__ . '/../view/layout/unauthorized_layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
            'home' => __DIR__ . '/../view/application/home/index.phtml',
            'autenticacao' => __DIR__ . '/../view/application/autenticacao/',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    'router' => array(
        'routes' => array(

            'home' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/home[/][:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Home',
                        'action' => 'index',
                    ),
                ),
            ),

            /*'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Home',
                        'action' => 'index',
                    ),
                ),
            ),*/
            'login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action' => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action' => 'logout',
                    ),
                ),
            ),
            'registrar' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/registrar',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action' => 'register',
                    ),
                ),
            ),

            'usuario' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/usuario[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '(.)+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Usuario',
                        'action' => 'index',
                    ),
                ),
            ),

            'alterarSenha' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/alterarSenha[/:userkey]',
                    'constraints' => array(
                        'userkey' => '[a-zA-Z0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action' => 'alterarSenha',
                    ),
                ),
            ),

            'grupo' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/grupo[/][:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Grupo',
                        'action' => 'index',
                    ),
                ),
            ),

            'unidade' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/unidade[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Unidade',
                        'action' => 'index',
                    ),
                ),
            ),

            'orgao' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/orgao[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Orgao',
                        'action' => 'index',
                    ),
                ),
            ),


            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Home',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'session_config' => array(
        'cache_expire' => 60 * 60,
        'cookie_lifetime' => 60 * 60,
    ),

    'translator' => array(
        'locale' => 'pt_BR',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),

    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(),
        ),
    ),
);
