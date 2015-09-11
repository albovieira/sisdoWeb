<?php

namespace Application\Util\EmailUtil;

use Zend\Di\ServiceLocatorInterface;
use Zend\Mail;
use Zend\Mime\Message as MimeMessage;

/**
 * Class EmailUtil.
 */
class EmailUtil
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * @var string
     */
    private $nomeSender;

    /**
     * @var string
     */
    private $emailSender;

    /**
     * Construtor da classe
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Retorna instancia do serviceLocator
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Atribui nome e email do remetente
     *
     * @param $nome
     * @param $email
     *
     * @return $this
     */
    public function setSender($nome, $email)
    {
        $this->nomeSender = $nome;
        $this->emailSender = $email;

        return $this;
    }

    /**
     * Retorna instancia da classe Smtp
     *
     * @return Mail\Transport\Smtp
     */
    public function getTransport()
    {
        return $this->getServiceLocator()->get('Zend\Mail\Transport\Smtp');
    }

    /**
     * Realiza o envio do email
     *
     * @param $nome
     * @param $email
     * @param $assunto
     * @param $conteudo
     *
     * @return $this
     */
    public function sendMail($nome, $email, $assunto, $conteudo)
    {
        $message = new \Zend\Mail\Message();

        $htmlPart = new \Zend\Mime\Part($conteudo);
        $htmlPart->type = 'text/html';

        $body = new MimeMessage();
        $body->setParts(array($htmlPart));

        $message->setFrom($this->emailSender, $this->nomeSender);
        $message->addTo($email, $nome);
        $message->setSubject($assunto);
        $message->setBody($body);

        $this->getTransport()->send($message);

        return $this;
    }

    /**
     * Retorna nome do remetente
     *
     * @return mixed
     */
    public function getNomeSender()
    {
        return $this->nomeSender;
    }

    /**
     * Atribui nome do remetente
     *
     * @param $nomeSender
     * @return $this
     */
    public function setNomeSender($nomeSender)
    {
        $this->nomeSender = $nomeSender;

        return $this;
    }

    /**
     * Retorna email do rementente
     *
     * @return mixed
     */
    public function getEmailSender()
    {
        return $this->emailSender;
    }

    /**
     * Atribui email do remetente
     *
     * @param $emailSender
     * @return $this
     */
    public function setEmailSender($emailSender)
    {
        $this->emailSender = $emailSender;

        return $this;
    }
}
