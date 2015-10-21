<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sisdo\Controller;

use Application\Custom\ActionControllerAbstract;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PersonController extends ActionControllerAbstract
{
    public function indexAction()
    {

        return new ViewModel(
            array(

            )
        );
    }

    public function paginaAction(){


        return new ViewModel();
    }

    /**
     * Retorna o titulo da pagina (especializar)
     *
     * @return mixed
     */
    public function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    /**
     * @return mixed
     */
    public function getBreadcrumb()
    {
        // TODO: Implement getBreadcrumb() method.
    }

    public function getTestes(){
        return new JsonModel('ts');
    }

}
