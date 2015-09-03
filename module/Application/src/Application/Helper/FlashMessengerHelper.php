<?php

/**
 * Created by PhpStorm.
 * User: BASIS-BH
 * Date: 16/04/2015
 * Time: 17:49.
 */

namespace Application\Helper;

use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Helper\AbstractHelper;

/**
 * Class FlashMessengerHelper.
 */
class FlashMessengerHelper extends AbstractHelper
{
    /**
     * @var \Zend\Mvc\Controller\Plugin\FlashMessenger()
     */
    private $flashMessenger;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $notifies;

    /**
     * @var bool
     */
    private $toList;

    /**
     * @param null $namespace
     * @param bool $toList
     *
     * @return mixed
     */
    public function __invoke($namespace = null, $toList = false)
    {
        $this->flashMessenger = new FlashMessenger();

        $this->namespace = $namespace;
        $this->toList = $toList;

        $this->showAlerts();

        return $this->notifies;
    }

    /**
     * Exibe os alertas formatados
     */
    public function showAlerts()
    {
        if (!$this->namespace) {
            $types = array(
                FlashMessenger::NAMESPACE_DEFAULT,
                FlashMessenger::NAMESPACE_SUCCESS,
                FlashMessenger::NAMESPACE_INFO,
                FlashMessenger::NAMESPACE_WARNING,
                FlashMessenger::NAMESPACE_ERROR,
            );
        } else {
            $types = array($this->namespace);
            $this->toList = true;
        }

        foreach ($types as $type) {

            $messages = $this->flashMessenger->getMessages($type);
            if (count($messages)) {
                foreach ($messages as $message) {
                    $this->formatAlert($message, $type);
                }
            }
        }
    }

    /**
     * Formata o alerta segundo o $type informado
     *
     * @param $message
     * @param string $type
     */
    private function formatAlert($message, $type = FlashMessenger::NAMESPACE_DEFAULT)
    {
        $typeToFor = array(
            FlashMessenger::NAMESPACE_DEFAULT => 'info',
            FlashMessenger::NAMESPACE_SUCCESS => 'success',
            FlashMessenger::NAMESPACE_INFO => 'info',
            FlashMessenger::NAMESPACE_WARNING => 'warning',
            FlashMessenger::NAMESPACE_ERROR => 'danger',
        );

        if (!$this->toList) {
            $this->notifies .= "$.notify({message: '$message'},{type:'{$typeToFor[$type]}', z_index: 2050})\n";
        } else {
            $this->notifies .= "<li>{$message}</li>";
        }

    }

}