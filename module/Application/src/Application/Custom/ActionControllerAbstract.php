<?php

namespace Application\Custom;

use Application\Util\Mensagens;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class ActionControllerAbstract.
 */
abstract class ActionControllerAbstract extends AbstractActionController
{
    /**
     * Executa a requisicao
     *
     * @param \Zend\Mvc\MvcEvent $e
     *
     * @return mixed
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $actionResponse = parent::onDispatch($e);
        $this->layout()->setVariable('breadcrumb', $this->getBreadcrumbOutput());
        $this->layout()->setVariable('title', $this->montarTitle($this->getTitle()));
        $this->layout()->setVariable('pageDescription', $this->getPageDescription());

        return $actionResponse;
    }

    /**
     * Seta o titulo da página, o qual é renderizado em tempo de execução no layout.
     *     * @param string $title
     */
    public function setTitle($title)
    {
        $this->getServiceLocator()
            ->get('ViewHelperManager')
            ->get('HeadTitle')
            ->set($title);
    }

    /**
     * Monta a string de titulo para sessoes de conteudo do sistema se mais de um nivel e fornecido(array).
     *
     * @param $title
     *
     * @return string
     */
    public function montarTitle($title)
    {
        if (is_array($title)) {
            $title = implode("<span class='separadorAcoes'> <i class='fa fa-angle-right'></i></span><small>", $title);
        }
        $title .= "</small>";
        return $title;
    }

    /**
     * Retorna o titulo da pagina (especializar)
     *
     * @return mixed
     */
    abstract public function getTitle();

    /**
     * Retorna a descricao da pagina (especializar)
     *
     * @return string
     */
    public function getPageDescription()
    {
        return '';
    }


    /**
     * @return mixed
     */
    public function getBreadcrumb()
    {
        die('s');
        //var_dump($this->getTitle());;die;
        $rt = array($this->getTitle());
        //$rt = array_merge($rt,array($this->getTitle()));
        //if($this->subTitle) {
        //    $rt = array_merge($rt,array($this->subTitle));
        ///}
        return $rt;
    }

    /**
     * Renderiza saida Html do breadcrumb (especializar)
     *
     * @return array|string
     */
    private function getBreadcrumbOutput()
    {
        $breadInicio = '<i class="ace-icon fa fa-home home-icon"></i> ';
        $breadInicio .= '<a href="/">' . 'Home' . '</a>';
        $breadInicio = array($breadInicio);

        $breadcrumb = '';

        if(is_array($this->getTitle())){
            $breadcrumb = $this->getTitle();
        }

        if (!empty($breadcrumb)) {
            return array_merge($breadInicio, $breadcrumb);
        } else {
            return $breadInicio;
        }
    }

    /**
     * Retira layout e view.
     *
     * @param string $content
     *
     * @return mixed
     */
    protected function noRender($content)
    {
        return $this->getResponse()->setContent($content);
    }

    /**
     * Retorna uma array com a configuracao dos botoes de acao a serem adicionados a uma listagem (grid)
     * O array gerado eh consumido pelo viewHelper Application\Helper\BotoesListagem.
     *
     * @return array
     */
    protected function getBotoesHelper()
    {
        return array();
    }

    /**
     * Metodo de auxilio para contrucao do array consumido pelo viewHelper Application\Helper\BotoesHelper.
     *
     * @param $classBotao
     * @param $classIcone
     * @param $alt
     * @param string $title
     * @param string $href
     * @param bool $hasTexto
     *
     * @return array
     */
    protected function addBotaoHelper($classBotao, $classIcone, $alt, $title = '', $href = '', $hasTexto = false, $attr = false)
    {
        if (empty($title)) {
            $title = $alt;
        }

        return array(
            'class-botao' => $classBotao,
            'class-icone' => $classIcone,
            'alt' => $alt,
            'title' => $title,
            'href' => $href,
            'hasTexto' => $hasTexto,
            'attr' => $attr
        );
    }

    /**
     * Obtem uma dependencia registrada no serviceLocator
     *
     * @param $name
     *
     * @return array|object
     */
    public function getFromServiceLocator($name)
    {
        return $this->getServiceLocator()->get($name);
    }

    /**
     * Retorna true se requisicao e encerrada sem a renderizacao da aplicacao
     *
     * @param bool $default
     *
     * @return mixed
     */
    protected function isTerminal($default = false)
    {
        return filter_var($this->params()->fromQuery('terminal', $default), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Retorna true se a requisicao utiliza o metodo POST
     *
     * @return bool
     */
    protected function isPost()
    {
        return $this->getRequest()->isPost();
    }

    /**
     * Recupera o Post
     *
     * @return bool
     */
    protected function getPost($post)
    {
        return $this->params()->fromPost($post);
    }

    /**
     * Retorna a mensagem de sistema para o identificador fornecido
     *
     * @param $identificador
     * @param array $valores
     *
     * @return mixed
     */
    public function getMensagem($identificador, array $valores = null)
    {
        return Mensagens::getMensagem($identificador, $valores);
    }

    /**
     * Retorna a url base da aplicacao
     *
     * @return string
     */
    public function getUrlBase()
    {
        $urlBase = $this->getRequest()->getUri();
        return $urlBase->getScheme() . '://' . $urlBase->getHost() . $this->request->getBasePath();
    }

    /**
     * Carrega uma imagem remota e retorna em formato base64 para ser disponibilizada em um documento com a tag <img>.
     *
     * @param $url
     *
     * @return string
     */
    protected function base64ImagemRemota($url)
    {
        $type = pathinfo($url, PATHINFO_EXTENSION);
        $data = file_get_contents($url);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
