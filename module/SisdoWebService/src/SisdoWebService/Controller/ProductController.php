<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SisdoWebService\Controller;

use Application\Custom\ActionControllerAbstract;
use Sisdo\Constants\ProductConst;
use Sisdo\Entity\Product;
use Sisdo\Entity\Unidade;
use Sisdo\Service\ProductService;
use Zend\View\Model\JsonModel;

class ProductController extends ActionControllerAbstract
{

    public function getDonationAction(){
        /** @var ProductService $service */
        $service = $this->getFromServiceLocator(ProductConst::SERVICE);

        $id = $this->params()->fromQuery('id');
        /** @var Product $doacao */
        $doacao = $service->getProduto($id);;

        $retorno[ProductConst::FLD_ID_PRODUTO] = $doacao->getIdProduto();
        $retorno[ProductConst::FLD_TITLE] = $doacao->getTitle();
        $retorno[ProductConst::FLD_INSTITUICAO] = $doacao->getInstitutionUser()->getUserId();
        $retorno[ProductConst::FLD_DESC] = $doacao->getDescription();
        $retorno[ProductConst::FLD_UNITY] = Unidade::getUnidadeBySigla($doacao->getUnity());
        $retorno[ProductConst::FLD_DATE] = $doacao->getDate()->format('d/m/y');
        $retorno[ProductConst::FLD_QTD] = $doacao->getQuantity();
        $retorno[ProductConst::FLD_TIPO] = $doacao->getProductType()->getDescription();

        return new JsonModel($retorno);
    }

    public function getDonationsByInstitutionAction(){
        /** @var ProductService $service */
        $service = $this->getFromServiceLocator(ProductConst::SERVICE);

        $id = $this->params()->fromQuery('id');
        $doacoes = $service->getProdutosAtivos($id);

        $retorno = [];
        if($doacoes){
            /** @var Product $doacao */
            foreach($doacoes as $doacao){
                $retorno[$doacao->getIdProduto()][ProductConst::FLD_ID_PRODUTO] = $doacao->getIdProduto();
                $retorno[$doacao->getIdProduto()][ProductConst::FLD_TITLE] = $doacao->getTitle();
                $retorno[$doacao->getIdProduto()][ProductConst::FLD_DATE] = $doacao->getDate()->format('d/m/y');
                $retorno[$doacao->getIdProduto()][ProductConst::FLD_TIPO] = $doacao->getProductType()->getDescription();
            }
        }
        else{
            $retorno = array('Sem Doacoes Cadastrados');
        }

        return new JsonModel($retorno);
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


}
