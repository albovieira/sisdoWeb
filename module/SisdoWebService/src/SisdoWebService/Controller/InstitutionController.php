<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SisdoWebService\Controller;

use Application\Constants\UsuarioConst;
use Application\Custom\ActionControllerAbstract;
use Sisdo\Constants\InstitutionConst;
use Sisdo\Constants\ProductConst;
use Sisdo\Entity\Institution;
use Sisdo\Entity\RamoInstituicao;
use Sisdo\Service\InstitutionService;
use Zend\View\Model\JsonModel;

class InstitutionController extends ActionControllerAbstract
{
    public function getInstitutionAction(){
        /** @var InstitutionService $service */
        $service = $this->getFromServiceLocator(InstitutionConst::SERVICE);

        $id = $this->params()->fromQuery('id');
        /** @var Institution $instituicao */
        $instituicao = $service->getInstitutionById($id);
        $instituicao = reset($instituicao);
        $instArray['id'] = $id;
        $instArray[InstitutionConst::FLD_BRANCH] = RamoInstituicao::getRamoById($instituicao->getBranch());
        $instArray[InstitutionConst::FLD_CNPJ] =  $instituicao->getCnpj();
        $instArray[InstitutionConst::FLD_PICTURE] = $instituicao->getPicture();
        $instArray[InstitutionConst::FLD_FANCY_NAME] = $instituicao->getFancyName();
      //  $instArray['about'] = $instituicao->getAbout();
        $instArray[UsuarioConst::FLD_EMAIL] = $instituicao->getUserId()->getEmail();
        //$instArray['telefone'] = $instituicao->getUserId()->getContact()->getTel();

        $instArray['hasDonativos'] = count($this->getFromServiceLocator(ProductConst::DAO)->getRepository()->findBy(array('institutionUser' => $instituicao->getUserId()))) > 0
        ? true : false;

        return new JsonModel($instArray);

    }

    public function getInstitutionsAction(){
        /** @var InstitutionService $service */
        $service = $this->getFromServiceLocator(InstitutionConst::SERVICE);

        $instituicoes = $service->getAllInstitutions();

        $instArray = [];
        /** @var Institution $instituicao */
        foreach($instituicoes as $instituicao){
            $instArray[$instituicao->getId()]['id'] = $instituicao->getUserId()->getId();
            $instArray[$instituicao->getId()][InstitutionConst::FLD_BRANCH] = RamoInstituicao::getRamoById($instituicao->getBranch());
            $instArray[$instituicao->getId()][InstitutionConst::FLD_CNPJ] = $instituicao->getCnpj();
            $instArray[$instituicao->getId()][InstitutionConst::FLD_PICTURE] = $instituicao->getPicture();
            $instArray[$instituicao->getId()][InstitutionConst::FLD_FANCY_NAME] = $instituicao->getFancyName();
        }

        return new JsonModel($instArray);

    }

    public function getInstituctionAutoCompleteAction(){

        /** @var InstitutionService $service */
        $service = $this->getFromServiceLocator(InstitutionConst::SERVICE);

        $term = $this->params()->fromQuery('term');
        $isCompleto = $this->params()->fromQuery('completo') != null ? $this->params()->fromQuery('completo') : false;
        $instituicoes = $service->getInstitutionsByTerm($term);

        $namesInstituicao = [];
        if(!$isCompleto){
            /** @var Institution $instituicao */
            foreach($instituicoes as $instituicao){
                $namesInstituicao[InstitutionConst::FLD_FANCY_NAME] = $instituicao->getFancyName();
            }
        }else{
            /** @var Institution $instituicao */
            foreach($instituicoes as $instituicao){
                $namesInstituicao[$instituicao->getUserId()->getId()][InstitutionConst::FLD_USER_ID] = $instituicao->getUserId()->getId();
                $namesInstituicao[$instituicao->getUserId()->getId()][InstitutionConst::FLD_FANCY_NAME]= $instituicao->getFancyName();
                $namesInstituicao[$instituicao->getUserId()->getId()][InstitutionConst::FLD_BRANCH] = RamoInstituicao::getRamoById($instituicao->getBranch());
                $namesInstituicao[$instituicao->getUserId()->getId()][InstitutionConst::FLD_PICTURE] = $instituicao->getPicture();
            }
        }

        return new JsonModel(
            $namesInstituicao
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


}
