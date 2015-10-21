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
use Application\Entity\User;
use Application\Service\UsuarioService;
use Sisdo\Entity\Adress;
use Sisdo\Entity\Person;
use Zend\View\Model\JsonModel;

class AuthenticationController extends ActionControllerAbstract
{


    public function loginAction(){

        /** @var UsuarioService $service */
        $service = $this->getFromServiceLocator(UsuarioConst::SERVICE);

        $post = $this->getRequest()->getPost();

        /** @var User $usuario */
        $usuario = $service->findByLogin($post['email']);

        /*
        $bcrypt = new Bcrypt();
        //$bcrypt->setCost($this->getOptions()->getPasswordCost());
        if (!$bcrypt->verify($post['pass'],'122CClJ2BVcSo')) {
            $teste = 'false';
        }else{
            $teste = 'teste';
        }
        */

        /** @var Person $pessoa */
        //$pessoa = $usuario->getPerson();

        /** @var Adress $adress */
        //$adress = $usuario->getAdress();

        if($usuario){
            $token = 'identificado';
        }else{
            $token = 'nidentificado';

        }

        return new JsonModel(
            array(
                'token' => $token
            )
        );
    }

    public function indexAction()
    {

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
