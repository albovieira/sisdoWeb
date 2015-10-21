<?php

/**
 * Created by PhpStorm.
 * User: BASIS-BH
 * Date: 20/05/2015
 * Time: 16:30.
 */

namespace Application\Service;

use Application\Constants\GridConst;
use Application\Constants\RotasConst as Rotas;
use Application\Constants\UnidadeConst;
use Application\Constants\UsuarioConst as Usuario;
use Application\Custom\ServiceAbstract;
use Application\Dao\UnidadeDao;
use Application\Dao\UsuarioDao;
use Application\Entity\Cargo;
use Application\Form\UsuarioForm;
use Application\Util\Datatable\DatatableColumn;
use Application\Util\Datatable\DatatableHtmlButton;
use Application\Util\Datatable\DatatableHtmlContainer;
use Application\Util\Datatable\DatatableQuery;
use Application\Util\Datatable\DatatableTable;
use Application\Util\Mensagens;
use Zend\Crypt\Password\Bcrypt;
use ZfcUser\Options\UserControllerOptionsInterface;

/**
 * Class UsuarioService
 * @package Application\Service
 */
class UsuarioService extends ServiceAbstract
{

    /**
     * @var UserControllerOptionsInterface
     */
    private $options;

    const ZFCUSER_USER_SERVICE = 'zfcuser_user_service';
    const ENTITY_MANAGER = 'Doctrine\ORM\EntityManager';

    public $tbAlias = 'u';
    /**
     * Cria e retorna o html + script para exibição do datatable.
     *
     * @return string
     */
    public function getGrid()
    {

        /** @var UsuarioDao $usuarioDao */
        $usuarioDao = $this->getFromServiceLocator(Usuario::DAO);

        $cId = $usuarioDao->createColumnName(Usuario::FLD_TBL_ID, true);
        $cNome = $usuarioDao->createColumnName(Usuario::FLD_NOME, true);
        $cCargo = $usuarioDao->createColumnName(Usuario::FLD_CARGO, true);
        $cOrgao = $usuarioDao->createColumnName(Usuario::FLD_ORGAO, true);
        $cUnidade = $usuarioDao->createColumnName(Usuario::FLD_UNIDADE, true);
        $cEmail = $usuarioDao->createColumnName(Usuario::FLD_EMAIL, true);
        $cTelefone = $usuarioDao->createColumnName(Usuario::FLD_TELEFONE, true);
        $cDataCad = $usuarioDao->createColumnName(Usuario::FLD_DTCADASTRO, true);
        $cAcoes = 'acoes';

        $colNome = new DatatableColumn($cNome, Usuario::LBL_NOME);
        $colCargo = new DatatableColumn($cCargo, Usuario::LBL_CARGO);
        $colOrgao = new DatatableColumn($cOrgao, Usuario::LBL_ORGAO);
        $colUnidade = new DatatableColumn($cUnidade, Usuario::LBL_UNIDADE);
        $colEmail = new DatatableColumn($cEmail, Usuario::LBL_EMAIL);
        $colTelefone = new DatatableColumn($cTelefone, Usuario::LBL_TELEFONE);
        $colDataCad = new DatatableColumn($cDataCad, Usuario::LBL_DTCADASTRO);
        $colAcoes = new DatatableColumn($cAcoes, 'Ações');
        $colAcoes->setSortable(false);
        $colAcoes->setWidth(GridConst::DEFAULT_WIDTH_ACOES);
        $colAcoes->addClass(DatatableColumn::CLASS_HEADER_ALIGN_CENTER, DatatableColumn::CLASS_BODY_ALIGN_CENTER);

        $grid = new DatatableTable($this->url('usuario', array('action' => 'filtro')), $this->serviceLocator);
        $grid->addColuna($colNome, $colCargo, $colOrgao, $colUnidade, $colEmail, $colTelefone, $colDataCad, $colAcoes);
        $grid->setCaption('Pesquisar Usuário');
        $grid->setShowSearch(true);
        $grid->setSearchInputSelectOptions($this->getFiltroPesquisa());
        $grid->setDefaultSort($cNome, DatatableTable::SORT_DESC);

        return $grid->render();
    }

    /**
     * Retorna o um array referente a uma consulta no padrão do datatables para o painel.
     *
     * @return array
     */
    public function getGridFiltro()
    {
        /** @var UsuarioDao $usuarioDao */
        $usuarioDao = $this->getFromServiceLocator(Usuario::DAO);

        $cId = $usuarioDao->createColumnName(Usuario::FLD_TBL_ID);
        $cNome = $usuarioDao->createColumnName(Usuario::FLD_NOME);
        $cCargo = $usuarioDao->createColumnName(Usuario::FLD_CARGO);
        $cOrgao = $usuarioDao->createColumnName(Usuario::FLD_ORGAO);
        $cUnidade = $usuarioDao->createColumnName(Usuario::FLD_UNIDADE);
        $cEmail = $usuarioDao->createColumnName(Usuario::FLD_EMAIL);
        $cTelefone = $usuarioDao->createColumnName(Usuario::FLD_TELEFONE);
        $cDataCad = $usuarioDao->createColumnName(Usuario::FLD_DTCADASTRO);
        $cAcoes = 'acoes';

        $qb = $usuarioDao->getCompleteQueryBuilder();
        $gridQuery = new DatatableQuery($this->getServiceLocator());
        $gridQuery->setQueryBuilder($qb);

        $paramsPost = $gridQuery->getParametrosFromPost();
        $filtrosPesquisa = array_keys($this->getFiltroPesquisa());
        $primeiroCampoFiltroPesquisa = array_shift($filtrosPesquisa);
        $campoPesquisaRapida = $gridQuery->getCampoPesquisa($paramsPost, $primeiroCampoFiltroPesquisa);

        if ($paramsPost[DatatableQuery::PARAM_SEARCH][DatatableQuery::PARAM_SEARCH_CAMPO] == $cDataCad) {
            $data = $paramsPost[DatatableQuery::PARAM_SEARCH][DatatableQuery::PARAM_SEARCH_VALOR];
            $paramsPost[DatatableQuery::PARAM_SEARCH][DatatableQuery::PARAM_SEARCH_VALOR]
                = implode('-', array_reverse(explode('/', $data)));
        }

        if (!empty($paramsPost[DatatableQuery::PARAM_SEARCH][DatatableQuery::PARAM_SEARCH_VALOR]) &&
            strcmp($paramsPost[DatatableQuery::PARAM_SEARCH][DatatableQuery::PARAM_SEARCH_CAMPO], $this->getTbAlias() . '.' . Usuario::FLD_CARGO) == 0
        ) {
            $searches = Cargo::search($paramsPost[DatatableQuery::PARAM_SEARCH][DatatableQuery::PARAM_SEARCH_VALOR]);
            foreach ($searches as $k => $search) {
                $gridQuery->addOrWhere($this->getTbAlias() . '.' . Usuario::FLD_CARGO, DatatableQuery::OPERATOR_CONTAINS, strtolower($search));
            }
        } else {
            $gridQuery->setPesquisaRapidaCampoOperacao($campoPesquisaRapida);
            $gridQuery->setUnicoParametro($paramsPost);
        }

        $gridQuery->setOrdenacaoReplace(array($this->getTbAlias() . '.' . Usuario::FLD_UNIDADE => Usuario::FLD_UNIDADE_OBJ_NOME));
        $gridQuery->setOrdenacaoReplace(array($this->getTbAlias() . '.' . Usuario::FLD_ORGAO => Usuario::FLD_ORGAO_OBJ_NOME));

        $retorno = $gridQuery->getDatatableArray();

        $data = array();

        foreach ($retorno[DatatableQuery::PARAM_REGISTROS] as $row) {
            /** @var \Application\Entity\Usuario $usuario */
            $usuario = is_a($row, '\Application\Custom\EntityAbstract') ? $row : reset($row);

            $temp[$cId] = $usuario->getSeqUsuario();
            $temp[$cNome] = $usuario->getNomUsuario();
            $temp[$cCargo] = Cargo::getLabel($usuario->getSigCargo());
            $temp[$cOrgao] = $usuario->getUnidade()->getOrgao()->getNomeOrgao();
            $temp[$cUnidade] = $usuario->getUnidade()->getNomeUnidade();
            $temp[$cEmail] = $usuario->getEmail();
            $temp[$cTelefone] = $usuario->getTelUsuario();
            $temp[$cDataCad] = date_format($usuario->getDataInclusao(), 'd/m/Y');

            $urlEditar = $this->url(Rotas::USUARIO, array('action' => 'editar', 'id' => $usuario->getLoginUsuario()));
            $botaoExpedir = new DatatableHtmlButton($urlEditar);
            $botaoExpedir->setTitle('Editar');
            $botaoExpedir->setTipoEditar();

            $urlExcluir = $this->url(Rotas::USUARIO, array('action' => 'excluir', 'id' => $usuario->getLoginUsuario()));
            $botaoExcluir = new DatatableHtmlButton($urlExcluir);
            $botaoExcluir->setTitle('Excluir');
            $botaoExcluir->setTipoExcluir();
            $botaoExcluir->setRequireConfirmation(true, Mensagens::getMensagem('M16'));


            $acoes = new DatatableHtmlContainer(DatatableHtmlContainer::TIPO_BUTTONS);
            $acoes->add($botaoExpedir);
            $acoes->add($botaoExcluir);

            $temp[$cAcoes] = $acoes->render();

            $data[] = $temp;
        }

        $retorno[DatatableQuery::PARAM_REGISTROS] = $data;

        return $retorno;
    }

    /**
     * Retorna campos, e respectivos labels, disponiveis para pesquisa no grid
     *
     * @return array
     */
    private function getFiltroPesquisa()
    {
        return array(
            Usuario::FLD_ORGAO_OBJ_NOME => Usuario::LBL_ORGAO,
            Usuario::FLD_UNIDADE_OBJ_NOME => Usuario::LBL_UNIDADE,
            $this->getTbAlias(Usuario::FLD_NOME) => Usuario::LBL_NOME,
            $this->getTbAlias(Usuario::FLD_CARGO) => Usuario::LBL_CARGO,
            $this->getTbAlias(Usuario::FLD_DTCADASTRO) => Usuario::LBL_DTCADASTRO,
        );
    }

    /**
     * Persiste o usuario no database
     *
     * @param array $data
     * @return Usuario|bool
     */
    public function incluir(array $data)
    {
        $usuario = new \Application\Entity\User();

        /** @var UsuarioForm $form */
        $form = $this->getFromServiceLocator('user_edit_form');
        $form->bind($usuario);
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        /** @var \Application\Entity\User $usuario */
        $usuario = $form->getData();

        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->getOptions()->getPasswordCost());
        $usuario->setPassword($bcrypt->create($usuario->getPassword()));

        if ($this->getOptions()->getEnableUsername()) {
            $usuario->setUsername($data[Usuario::FLD_LOGIN]);
        }
        if ($this->getOptions()->getEnableDisplayName()) {
            $usuario->setDisplayName($data[Usuario::FLD_NOME]);
        }

        //por enquanto so instituicao pode se cadatrar
        $usuario->setProfile(1);
        $dataAtual = new \DateTime('now');
        $usuario->setDate($dataAtual->format('Y-m-d'));

        /** @var AutenticacaoService $service */
        $service = $this->getFromServiceLocator(self::ZFCUSER_USER_SERVICE);

        $service->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $usuario, 'form' => $form));
        $service->getUserMapper()->insert($usuario);
        $service->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $usuario, 'form' => $form));


        return $usuario;
    }

    /**
     * Altera um usuario no database
     *
     * @param array $data
     * @return bool
     */
    public function editar(array $data)
    {
        /** @var \Application\Entity\Usuario $usuario */
        $usuario = $this->findById($data[Usuario::FLD_SEQ_USUARIO]);
        $this->refresh($usuario);
        $pwd = $usuario->getPassword();


        /** @var  UsuarioForm $form */
        $form = $this->getFromServiceLocator('user_edit_form');
        $form->setObject($usuario);
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        /** @var Usuario $usuarioForm */
        $usuarioForm = $form->getData();

        /** @var AutenticacaoService $service */
        $service = $this->getFromServiceLocator(self::ZFCUSER_USER_SERVICE);

        if ($usuarioForm->getPassword()) {
            $bcrypt = new Bcrypt();
            $bcrypt->setCost($service->getOptions()->getPasswordCost());
            $usuarioForm->setPassword($bcrypt->create($usuarioForm->getPassword()));
        } else {
            $usuarioForm->setPassword($pwd);
        }

        return $service->getUserMapper()->update($usuarioForm);
    }

    /**
     * Remove um usuario do database
     * @param $usuario
     * @return mixed
     */
    public function excluir($usuario)
    {
        return $this->getFromServiceLocator(Usuario::DAO)->remove($usuario);
    }

    /**
     * Recupera usuario com o login informado
     *
     * @param string $login
     * @return Usuario
     */
    public function findByLogin($login)
    {
        /** @var AutenticacaoService $service */
        $service = $this->getFromServiceLocator(self::ZFCUSER_USER_SERVICE);
        return $service->getUserMapper()->findByEmail($login);
    }

    /**
     * Recupera o usuario a partir do Id
     *
     * @param string $UserId
     * @return Usuario
     */
    public function findById($UserId)
    {
        /** @var AutenticacaoService $service */
        $service = $this->getFromServiceLocator(self::ZFCUSER_USER_SERVICE);
        return $service->getUserMapper()->findById($UserId);
    }

    /**
     * Atualiza status do objeto
     *
     * @param $entity
     * @return mixed
     */
    public function refresh($entity)
    {
        /** @var AutenticacaoService $service */
        $service = $this->getFromServiceLocator(self::ZFCUSER_USER_SERVICE);
        return $service->getUserMapper()->refresh($entity);
    }

    public function getUnidadesList($seqOrgao)
    {
        /** @var UnidadeDao $unidade */
        $unidade = $this->getFromServiceLocator(UnidadeConst::DAO);
        return $unidade->findUnidadeByOrgao($seqOrgao);
    }

    /**
     * Retorna parametros de configuracao do componente ZfcUser
     *
     * @return UserControllerOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof UserControllerOptionsInterface) {
            $this->options = $this->getServiceLocator()->get('zfcuser_module_options');
        }
        return $this->options;
    }


}
