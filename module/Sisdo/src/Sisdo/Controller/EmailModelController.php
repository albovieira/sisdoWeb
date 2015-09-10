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
use Sisdo\Constants\RelationshipConst;
use Sisdo\Service\RelationshipService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class EmailModelController extends ActionControllerAbstract
{
    public function indexAction()
    {

        /** @var RelationshipService $service */
        $service = $this->getFromServiceLocator(RelationshipConst::SERVICE);
        $grid = $service->getGrid();
        return new ViewModel(
            array(
                'grid' => $grid,
                'botoesHelper' => $this->getBotoesHelper()
            )
        );
    }

    public function getDadosAction(){

        /** @var RelationshipService $service */
        $service = $this->getFromServiceLocator(RelationshipConst::SERVICE);
        $grid = $service->getGridDados();

        return new JsonModel($grid);
    }

    public function enviarEmailAction(){

    }

    public function getBotoesHelper()
    {
        return array(
            $this->addBotaoHelper('btn-incluir btn-primary btn btn-xs', 'glyphicon glyphicon-envelope', 'Enviar Email','',
                $this->url()->fromRoute('relacionamento', array('action' => 'enviarEmail'))),
            $this->addBotaoHelper('btn-incluir btn-success btn btn-xs', 'glyphicon glyphicon-plus', 'Incluir Modelo','',
                $this->url()->fromRoute('modeloemail', array('action' => 'incluir'))),
        );
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

}
