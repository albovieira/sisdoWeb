<?php

namespace Application\Custom;

use Application\Constants\FormConst;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\Url;

/**
 * Class ServiceAbstract.
 */
abstract class ServiceAbstract implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * @var string
     */
    protected $tbAlias = '';

    /**
     * @var Url
     */
    protected $url = null;

    /**
     * @param null $serviceLocator
     */
    public function __construct($serviceLocator = null)
    {
        if (isset($serviceLocator)) {
            $this->setServiceLocator($serviceLocator);

            $url = $serviceLocator->get('viewhelpermanager')->get('url');
            $this->url = $url;

        }

        return $this;
    }

    /**
     *
     * Generates a url given the name of a route.
     *
     * @see    Zend\Mvc\Router\RouteInterface::assemble()
     * @param  string $name Name of the route
     * @param  array $params Parameters for the link
     * @param  array|\Traversable $options Options for the route
     * @param  bool $reuseMatchedParams Whether to reuse matched parameters
     * @return string Url                         For the link href attribute
     * @throws \Zend\View\Exception\RuntimeException         If no RouteStackInterface was provided
     * @throws \Zend\View\Exception\RuntimeException         If no RouteMatch was provided
     * @throws \Zend\View\Exception\RuntimeException         If RouteMatch didn't contain a matched route name
     * @throws \Zend\View\Exception\InvalidArgumentException If the params object was not an array or \Traversable object
     *
     * @see    \Zend\View\Helper\Url::__invoke
     */
    public function url($name = null, $params = [], $options = [], $reuseMatchedParams = false)
    {
        $url = $this->url;
        return rawurldecode($url($name, $params, $options, $reuseMatchedParams));
    }


    /**
     * Set service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Retorna dependencia registrada para o serviceLocator
     *
     * @param $name
     *
     * @return array|object
     */
    public function getFromServiceLocator($name)
    {
        return $this->serviceLocator->get($name);
    }

    /**
     * Converte um array informado para um array par chave e valor a ser utilizado na composição
     * dos options de um campo select.
     *
     * @param array $dados
     * @param $indice
     * @param $label
     * @return array
     */
    public function montarArrayNomeadoSelect(array $dados, $indice, $label)
    {
        $options = array();
        $options[null] = FormConst::SELECT_OPTION_SELECIONE;
        foreach ($dados as $registro) {
            if (isset($registro[$indice]) && isset($registro[$label])) {
                $options[$registro[$indice]] = $registro[$label];
            }
        }

        return $options;
    }


}
