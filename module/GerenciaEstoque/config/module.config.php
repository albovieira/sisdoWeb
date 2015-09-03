<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace GerenciaEstoque;

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
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'GerenciaEstoque\Controller\Index',
                        'action'     => 'index',
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
                        '__NAMESPACE__' => 'GerenciaEstoque\Controller',
                        'controller' => 'Produto',
                        'action' => 'index',
                    ),
                ),
            ),
            'estoque' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/estoque[/][:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'GerenciaEstoque\Controller',
                        'controller' => 'Estoque',
                        'action' => 'index',
                    ),
                ),
            ),
            'fornecedor' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/fornecedor[/][:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'GerenciaEstoque\Controller',
                        'controller' => 'Fornecedor',
                        'action' => 'index',
                    ),
                ),
            ),

            'pedido' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pedido[/][:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'GerenciaEstoque\Controller',
                        'controller' => 'Pedido',
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
                        '__NAMESPACE__' => 'GerenciaEstoque\Controller',
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
            'ProdutoDao' => function ($sm) {
                return new \GerenciaEstoque\Dao\ProdutoDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'EstoqueDao' => function ($sm) {
                return new \GerenciaEstoque\Dao\EstoqueDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'FornecedorDao' => function ($sm) {
                return new \GerenciaEstoque\Dao\FornecedorDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'PedidoDao' => function ($sm) {
                return new \GerenciaEstoque\Dao\PedidoDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },
            'ItemPedidoDao' => function ($sm) {
                return new \GerenciaEstoque\Dao\ItemPedidoDao($sm->get('Doctrine\ORM\EntityManager'), $sm);
            },

            //Service
            'ProdutoService' => function ($sm) {
                return new \GerenciaEstoque\Service\ProdutoService($sm);
            },

            'EstoqueService' => function ($sm) {
                return new \GerenciaEstoque\Service\EstoqueService($sm);
            },

            'FornecedorService' => function ($sm) {
                return new \GerenciaEstoque\Service\FornecedorService($sm);
            },
            'PedidoService' => function ($sm) {
                return new \GerenciaEstoque\Service\PedidoService($sm);
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
            'GerenciaEstoque\Controller\Index' => 'GerenciaEstoque\Controller\IndexController',
            'GerenciaEstoque\Controller\Produto' => 'GerenciaEstoque\Controller\ProdutoController',
            'GerenciaEstoque\Controller\Estoque' => 'GerenciaEstoque\Controller\EstoqueController',
            'GerenciaEstoque\Controller\Fornecedor' => 'GerenciaEstoque\Controller\FornecedorController',
            'GerenciaEstoque\Controller\Pedido' => 'GerenciaEstoque\Controller\PedidoController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
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
