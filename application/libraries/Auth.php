<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

   /**
	* _ci
	*
	* @private 
	*/
    private $_ci;

   /**
	* __construct
	* 
	* metodo construtor
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function __construct() {
        
        // instancia do codeigniter
        $this->_ci =& get_instance();

        // carrega o crypt
        $this->_ci->load->library( 'Bcrypt' );

        // carrega a model
        $this->_ci->load->model( 'Funcionarios_model' );
    }

   /**
	* __validate
	* 
	* valida o usuario
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    private function __validate( $user ) {

        // gera um token aleatorio
        $user['hash_token']        = md5( uniqid( rand() * time() ).time());
        $user['date_ultimo_login'] = date( 'Y-m-d H:i:s', time() );
        $user['id']                = $user['funcionario_id'];

        // faz o update do usuario
        if ( $this->_ci->Funcionarios_model->update( $user ) ) {

            // guarda os dados do usuario
            $dados = [  'email' => $user['desc_email'],
                        'id'    => $user['funcionario_id'],
                        'nome'  => $user['desc_nome'],
                        'token' => $user['hash_token'],
                        'cargo' => $user['cargo_id'],
                        'foto'  => $user['hash_foto'] ];

            // salva o usuario na sessao
            $this->_ci->session->set_userdata( 'user', $dados );

            // volta verdadeiro
            return true;
        } else return false;
    }

   /**
	* login
	* 
	* faz o login
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function login( $email, $senha ) {

        // verifica se o usuario ja está logado
        if ( $this->is_logged() ) return false;

        // tenta pegar o usuario pelo email
        if ( $user = $this->_ci->Funcionarios_model->getUserByEmail( $email ) ) {

            // verifica se a senha é valida
            if ( $this->_ci->bcrypt->verify( $senha, $user['hash_senha']) ) {

                // valida o login
                return $this->__validate( $user );
            } else return false;            
        } else return false;
    }

   /**
	* is_logged
	* 
	* verifica se o usuario esta logado
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function is_logged() {
        if ( $email = $this->user( 'email' ) && $token = $this->user( 'token' ) ) {
            if ( $check = $this->_ci->Funcionarios_model->getUserByEmail( $this->user( 'email' ) ) ) {
                //verifica se o token do banco é igual o da sessão
                return (  $this->user( 'token' )  === $check['hash_token']);
            } else return false;
        } else return false;
    }

   /**
	* user
	* 
	* pega informações do usuario atual
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function user( $key = false) {
        // pega os dados da sessao
        if ( !is_null( $user = $this->_ci->session->userdata( 'user' ) ) ) {
            if ( !$key ) return $user;
            return ( isset( $user[$key] ) ) ? $user[$key] : false;
        } else return false;
    }

   /**
	* logout
	* 
	* faz o logout do sistema
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function logout() {
        $this->_ci->session->sess_destroy();
    }
}