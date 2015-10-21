<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SisdoWebService;

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

    'router' => array(
        'routes' => array(


            'token' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/token[/][:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'SisdoWebService\Controller',
                        'controller' => 'Autenticacao',
                        'action' => 'index',
                    ),
                ),
            ),


            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'SisdoWebService\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',

            //Daos
            'ProductDao' => function ($sm) {
                return new \Sisdo\Dao\ProductDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'InstitutionDao' => function ($sm) {
                return new \Sisdo\Dao\InstitutionDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'ContactDao' => function ($sm) {
                return new \Sisdo\Dao\ContactDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'AdressDao' => function ($sm) {
                return new \Sisdo\Dao\AdressDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'RelationshipDao' => function ($sm) {
                return new \Sisdo\Dao\RelationshipDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'TransactionDao' => function ($sm) {
                return new \Sisdo\Dao\TransactionDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'TemplateEmailDao' => function ($sm) {
                return new \Sisdo\Dao\TemplateEmailDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'ProductTypeDao' => function ($sm) {
                return new \Sisdo\Dao\ProductTypeDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'PersonDao' => function ($sm) {
                return new \Sisdo\Dao\PersonDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'MoneyDonationDao' => function ($sm) {
                return new \Sisdo\Dao\MoneyDonationDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'StateDao' => function ($sm) {
                return new \Sisdo\Dao\StateDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'CityDao' => function ($sm) {
                return new \Sisdo\Dao\CityDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },

            //Services
            'UsuarioService' => function ($sm) {
                return new \Application\Service\UsuarioService($sm);
            },
            'ProductService' => function ($sm) {
                return new \Sisdo\Service\ProductService($sm);
            },
            'InstitutionService' => function ($sm) {
                return new \Sisdo\Service\InstitutionService($sm);
            },
            'RelationshipService' => function ($sm) {
                return new \Sisdo\Service\RelationshipService($sm);
            },
            'TransactionService' => function ($sm) {
                return new \Sisdo\Service\TransactionService($sm);
            },
            'PersonService' => function ($sm) {
                return new \Sisdo\Service\PersonService($sm);
            },
            'MoneyDonationService' => function ($sm) {
                return new \Sisdo\Service\MoneyDonationService($sm);
            },
            'AdressService' => function ($sm) {
                return new \Sisdo\Service\AdressService($sm);
            },
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'SisdoWebService\Controller\Autenticacao' => 'SisdoWebService\Controller\AuthenticationController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/sisdo.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
