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
use Sisdo\Constants\MoneyDonationConst;
use Sisdo\Service\MoneyDonationService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class MoneyDonationController extends ActionControllerAbstract
{

    protected $title = array('Doação Financeira','Lista');

    public function indexAction()
    {
        /** @var MoneyDonationService $service */
        $service = $this->getFromServiceLocator(MoneyDonationConst::SERVICE);
        $grid = $service->getGrid();
        return new ViewModel(
            array(
                'grid' => $grid,
                // 'botoesHelper' => $this->getBotoesHelper()
            )
        );
    }

    public function getDadosAction()
    {
        /** @var MoneyDonationService $service */
        $service = $this->getFromServiceLocator(MoneyDonationConst::SERVICE);
        $grid = $service->getGridDados();

        return new JsonModel($grid);
    }

    public function incluirAction()
    {


    }

    public function getDadosRelatorioAction()
    {
        /** @var MoneyDonationService $service */
        $service = $this->getFromServiceLocator(MoneyDonationConst::SERVICE);
        $grid = $service->getGridDados(true);

        return new JsonModel($grid);
    }

    public function relatorioAction(){
        /** @var MoneyDonationService $service */
        $service = $this->getFromServiceLocator(MoneyDonationConst::SERVICE);

        $url = '/doacao-financeira/getDadosRelatorio';
        $grid = $service->getGrid(false,$url);

        return new ViewModel(
            array('grid' => $grid)
        );

    }

    /**
     * Retorna o titulo da pagina (especializar)
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getBreadcrumb()
    {
        // TODO: Implement getBreadcrumb() method.
    }

    public function getBotoesHelper()
    {
        /* return array(
             $this->addBotaoHelper('btn-incluir btn-success btn btn-xs', 'glyphicon glyphicon-plus', 'Incluir Doacao','',
                 $this->url()->fromRoute('produto', array('action' => 'incluir'))),
         );*/
    }

}
