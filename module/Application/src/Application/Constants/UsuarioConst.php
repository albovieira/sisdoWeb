<?php

namespace Application\Constants;

/**
 * Interface UsuarioConst.
 */
interface UsuarioConst
{

    const DAO = "UsuarioDao";
    const SERVICE = "UsuarioService";

    const ZFCUSER_REGISTER_FORM = "zfcuser_register_form";
    const ZFCUSER_AUTH_SERVICE = "zfcuser_auth_service";
    const EDIT_FORM = "user_edit_form";

    const FLD_TBL_ID = "seqUsuario";
    const LBL_TBL_ID = "Id";

    const FLD_SEQ_USUARIO = "userId";
    const LBL_SEQ_USUARIO = "Id Usuario";

    const FLD_ORGAO_OBJ_NOME = "o.nomeOrgao";
    const FLD_ORGAO = "fld_orgao";
    const LBL_ORGAO = "Órgão";

    const FLD_UNIDADE_OBJ_NOME = "un.nomeUnidade";
    const FLD_NOME_UNIDADE = "nomeUnidade";
    const FLD_UNIDADE = "unidade";
    const FLD_SEQ_UNIDADE = "seqUnidade";
    const LBL_UNIDADE = "Unidade";

    const FLD_EMAIL = "email";
    const LBL_EMAIL = "E-mail";

    const FLD_TELEFONE = "telUsuario";
    const LBL_TELEFONE = "Telefone";

    const FLD_LOGIN = "username";
    const LBL_LOGIN = "Login";

    const FLD_NOME = "name";
    const LBL_NOME = "Nome";

    const FLD_SENHA = "password";
    const LBL_SENHA = "Senha";

    const FLD_SENHA_VERIFY = "passwordVerify";
    const LBL_SENHA_VERIFY = "Confirme a senha";


    const FLD_DTCADASTRO = "date";
    const LBL_DTCADASTRO = "Data do Cadastro";

    /**
     * Formularios.
     *
     * Formulario de Login
     */
    const LOGIN_METHOD = 'POST';

    const LOGIN_ACTION = '/login';

    const PWD_RESET_ACTION = '/login';

    const FLD_IDENTITY = 'identity';

    const LBL_IDENTITY = 'Usuário';

    const FLD_REDIRECT = 'redirect';

    const FLD_ENTRAR = 'btnEntrar';
    const LBL_ENTRAR = 'Entrar';

    const FLD_ESQUECI_SENHA = 'Esqueci a Senha';

    const FLD_BTN_CONFIRMAR = 'btnConfirmar';
    const LBL_BTN_CONFIRMAR = 'Confirmar';

    const FLD_BTN_CANCELAR = 'btnCancelar';
    const LBL_BTN_CANCELAR = 'Cancelar';

    /**
     * Verificar Chave de Acesso.
     */
    const FLD_CHAVE_ACESSO = 'chaveAcesso';
    /**
     *
     */
    const LBL_CHAVE_ACESSO = 'Chave de Acesso';

    /**
     * Codigo de erros de acesso.
     */
    const ERRO_SENHA_EXPIRADA = -2;
    /**
     *
     */
    const ERRO_USUARIO_INATIVO = -6;
    /**
     *
     */
    const ERRO_PRIMEIRO_ACESSO = -7;

    /**
     *
     */
    const ERRO_CHAVE_ACESSO_INVALIDO = -8;
    /**
     *
     */
    const ERRO_EXCEDIDO_LIMITE_CHAVE_ACESSO = -9;
    /**
     *
     */
    const LBL_SENHA_ANTIGA = 'Senha Antiga';
    /**
     * Alterar Senha de Acesso.
     */
    const LBL_NOVA_SENHA = 'Nova Senha';
    /**
     *
     */
    const LBL_CONFIRM_NOVA_SENHA = 'Confirme a Senha';
}
