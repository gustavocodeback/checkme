<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

   /**
	* _public
	* 
	* @protected
	*/
	protected $_public = true;

   /**
	* __construct
	* 
	* metodo construtor
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function __construct() {
        	parent::__construct();
	}

   /**
	* index
	* 
	* metodo principal
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function index() {

		if ( $this->auth->is_logged() ) {
			redirect(site_url('rotinas'));
		} else {
			// exibe o login
			$this->_show( 'login', false );
		}
	}

   /**
	* logar
	* 
	* faz o login
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function logar() {

		// regras de validação
		$this->form_validation->set_rules('email', 'E-mail','required|valid_email');
		$this->form_validation->set_rules('senha', 'Senha', 'required|max_length[16]|min_length[1]');
		
		// verifica se o formulario é valido
		if ( $this->form_validation->run() === FALSE ) {

			// seta as mensagens de erro
			$this->_set( 'errors', validation_errors() );
		} else {

			// pega os dados do post
			$identity = $this->input->post('email');
			$password = $this->input->post('senha');

			// faz o login
			if ( $this->auth->login( $identity, $password ) ) {

				// redireciona para a pagina inicial
				redirect( site_url('rotinas/index') );
				exit();
			} else {
				// seta mensagem de erro
				$this->_set( 'errors', 'A senha ou o e-mail estão incorretos' );
			}
		}

		// exibe o login
		$this->_show( 'login', false );
	}

   /**
	* logout
	* 
	* faz o logout
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function logout() {
		$this->auth->logout();
		redirect( site_url('login') );
	}
}
