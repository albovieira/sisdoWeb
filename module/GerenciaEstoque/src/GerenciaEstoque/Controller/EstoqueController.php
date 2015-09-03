<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace GerenciaEstoque\Controller;

use Application\Constants\EstoqueConst;
use Application\Custom\ActionControllerAbstract;
use GerenciaEstoque\Service\EstoqueService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class EstoqueController extends ActionControllerAbstract
{
    public function indexAction()
    {
        /** @var EstoqueService $service */
        $service = $this->getFromServiceLocator(EstoqueConst::SERVICE);
        $grid = $service->getGrid();
        return new ViewModel(
            array(
                'grid' => $grid,
            )
        );
    }

    public function getDadosAction()
    {

        /** @var EstoqueService $service */
        $service = $this->getFromServiceLocator(ProdutoConst::SERVICE);
        $grid = $service->getGridDados();

        return new JsonModel($grid);
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
