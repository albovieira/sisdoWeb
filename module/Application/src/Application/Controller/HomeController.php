<?php

/**
 * Zend Framework (http://framework.zend.com/).
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Custom\ActionControllerAbstract;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController.
 */
class HomeController extends ActionControllerAbstract
{

    /**
     * @return ViewModel
     */
    public function indexAction()
    {

        //validar se o usuario é instituicao, por enquanto vamos fazer so a parte de instituicao.
        $this->redirect()->toRoute('principal');

        return new ViewModel();
    }

    /**
     * Retorna titulo da pagina
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Início';
    }

    /**
     * Retorna niveis de navegacao para composicao do breadcrumb
     *
     * @return array
     */
    public function getBreadcrumb()
    {
        return array();
    }

    /**
     * Altera o template para tela de erro.
     *
     */
    public function unauthorizedAction()
    {
        $view = new ViewModel();
        $view->setTemplate('error/403');

        return $view;
    }
}
