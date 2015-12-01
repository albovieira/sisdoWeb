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
use Sisdo\Constants\RelationshipConst;
use Sisdo\Service\RelationshipService;
use Sisdo\Service\TransactionService;
use Zend\View\Model\JsonModel;

class RelationshipController extends ActionControllerAbstract
{

    public function isFollowAction(){

        /** @var RelationshipService $service */
        $service = $this->getFromServiceLocator(RelationshipConst::SERVICE);

        $instituicao = $this->params()->fromQuery('instituicao');
        $user = $this->params()->fromQuery('user');

        return new JsonModel(array('isFollow' => $service->isSeguindo($instituicao,$user)));
    }

    public function seguirAction(){

        /** @var RelationshipService $service */
        $service = $this->getFromServiceLocator(RelationshipConst::SERVICE);
        $post = $this->getPost();

        $seguir = $service->seguir($post);

        return new JsonModel(array('isFollow' => $seguir));
    }

}
