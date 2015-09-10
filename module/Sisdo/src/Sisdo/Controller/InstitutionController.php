<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sisdo\Controller;

use Application\Constants\MensagemConst;
use Application\Constants\UsuarioConst;
use Application\Custom\ActionControllerAbstract;
use Sisdo\Constants\InstitutionConst;
use Sisdo\Filter\AdressFilter;
use Sisdo\Filter\ContactFilter;
use Sisdo\Filter\InstitutionFilter;
use Sisdo\Form\AdressForm;
use Sisdo\Form\ContactForm;
use Sisdo\Form\InstitutionForm;
use Sisdo\Service\InstitutionService;
use Zend\View\Model\ViewModel;

class InstitutionController extends ActionControllerAbstract
{
    public function indexAction()
    {

        /** @var \Application\Entity\User $instituicaoLogado */
        $instituicaoLogado = $this->getFromServiceLocator(UsuarioConst::ZFCUSER_AUTH_SERVICE)->getIdentity();

        $formInstitution = new InstitutionForm('/instituicao/salvar',$instituicaoLogado);
        if($instituicaoLogado->getInstituicao()){
            $formInstitution->bind($instituicaoLogado->getInstituicao());
        }

        $formContact = new ContactForm('/instituicao/salvarContato',$instituicaoLogado);
        if($instituicaoLogado->getContact()){
            $formContact->bind($instituicaoLogado->getContact());
        }

        $formAdress = new AdressForm('/instituicao/salvarEndereco',$instituicaoLogado);
        if($instituicaoLogado->getAdress()){
            $formAdress->bind($instituicaoLogado->getAdress());
        }

        return new ViewModel(
            array(
                'formInstitution' => $formInstitution,
                'formContact' => $formContact,
                'formAdress' => $formAdress
            )
        );
    }

    public function salvarAction(){
        /** @var InstitutionService $service */
        $service = $this->getFromServiceLocator(InstitutionConst::SERVICE);

        $post = $this->getRequest()->getPost();

        $formInstitution = new InstitutionForm();
        $formInstitution->setInputFilter(new InstitutionFilter());
        if ($this->isPost()) {
            $formInstitution->setData($post);
            if ($formInstitution->isValid()) {

                try {
                     if($service->salvar($formInstitution->getData())){
                         $this->flashMessenger()->addSuccessMessage(MensagemConst::OPERACAO_SUCESSO);
                     }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                }
            }
        }

        return $this->redirect()->toRoute('instituicao');

    }

    public function salvarContatoAction(){
        /** @var InstitutionService $service */
        $service = $this->getFromServiceLocator(InstitutionConst::SERVICE);

        $post = $this->getRequest()->getPost();

        $formContact = new ContactForm();
        $formContact->setInputFilter(new ContactFilter());
        if ($this->isPost()) {
            $formContact->setData($post);
            if ($formContact->isValid()) {

                try {
                    if($service->salvar($formContact->getData())){
                        $this->flashMessenger()->addSuccessMessage(MensagemConst::OPERACAO_SUCESSO);
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                }
            }
        }

        return $this->redirect()->toRoute('instituicao');

    }

    public function salvarEnderecoAction(){
        /** @var InstitutionService $service */
        $service = $this->getFromServiceLocator(InstitutionConst::SERVICE);

        $post = $this->getRequest()->getPost();

        $formAdress = new AdressForm();
        $formAdress->setInputFilter(new AdressFilter());
        if ($this->isPost()) {
            $formAdress->setData($post);
            if ($formAdress->isValid()) {

                try {
                    if($service->salvar($formAdress->getData())){
                        $this->flashMessenger()->addSuccessMessage(MensagemConst::OPERACAO_SUCESSO);
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(MensagemConst::OCORREU_UM_ERRO);
                }
            }
        }

        return $this->redirect()->toRoute('instituicao');

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
