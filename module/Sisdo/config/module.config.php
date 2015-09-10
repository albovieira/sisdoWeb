<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sisdo;

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


            'principal' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/principal',
                    'defaults' => array(
                        'controller' => 'Sisdo\Controller\Main',
                        'action'     => 'index',
                    ),
                ),
            ),

            'instituicao' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/instituicao[/][:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Sisdo\Controller',
                        'controller' => 'Instituicao',
                        'action' => 'index',
                    ),
                ),
            ),


            'relacionamento' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/relacionamento[/][:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Sisdo\Controller',
                        'controller' => 'Relacionamento',
                        'action' => 'index',
                    ),
                ),
            ),



            'transacao' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/transacao[/][:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Sisdo\Controller',
                        'controller' => 'Transacao',
                        'action' => 'index',
                    ),
                ),
            ),

            'produto' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/produto[/][:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Sisdo\Controller',
                        'controller' => 'Produto',
                        'action' => 'index',
                    ),
                ),
            ),

            'modeloemail' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/modeloemail[/][:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Sisdo\Controller',
                        'controller' => 'ModeloEmail',
                        'action' => 'index',
                    ),
                ),
            ),

            'ex' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/ex[/][:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Sisdo\Controller',
                        'controller' => 'NomeController',
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
                        '__NAMESPACE__' => 'Sisdo\Controller',
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

            //Services
            'ProductService' => function ($sm) {
                return new \Sisdo\Service\ProductService($sm);
            },
            'InstitutionService' => function ($sm) {
                return new \Sisdo\Service\InstitutionService($sm);
            },
            'RelationshipService' => function ($sm) {
                return new \Sisdo\Service\RelationshipService($sm);
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
            'Sisdo\Controller\Main' => 'Sisdo\Controller\MainController',
            'Sisdo\Controller\Instituicao' => 'Sisdo\Controller\InstitutionController',
            'Sisdo\Controller\Produto' => 'Sisdo\Controller\ProductController',
            'Sisdo\Controller\Transacao' => 'Sisdo\Controller\TransactionController',
            'Sisdo\Controller\Relacionamento' => 'Sisdo\Controller\RelationshipController',
            'Sisdo\Controller\ModeloEmail' => 'Sisdo\Controller\EmailModelController',
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
