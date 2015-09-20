<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sisdo\Controller;

use Application\Constants\ProfileConst;
use Application\Constants\UsuarioConst;
use Application\Custom\ActionControllerAbstract;
use Application\Entity\User;
use Sisdo\Constants\PersonConst;
use Sisdo\Constants\RelationshipConst;
use Sisdo\Service\PersonService;
use Sisdo\Service\RelationshipService;
use Zend\View\Model\ViewModel;

class MainController extends ActionControllerAbstract
{
    public function indexAction()
    {
        /** @var User $usuarioLogado */
        $usuarioLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        if ($usuarioLogado->getProfile() == ProfileConst::FLAG_PROFILE_PERSON) {
            return $this->redirect()->toRoute('inicio');
        }

        return new ViewModel(
            array(

            )
        );
    }

    public function inicioAction()
    {
        /** @var PersonService $service */
        $service = $this->getFromServiceLocator(PersonConst::SERVICE);

        /** @var RelationshipService $relationService */
        $relationService = $this->getFromServiceLocator(RelationshipConst::SERVICE);

        /** @var User $usuarioLogado */
        $usuarioLogado = $service->getUserLogado();

        $relacionamentos = $relationService->getRelationshipByUser();


        return new ViewModel(
            array(
                'usuarioLogado' => $usuarioLogado,
                'relacionamentos' => $relacionamentos
            )
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
