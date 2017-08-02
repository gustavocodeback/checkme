<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

   /**
	* __dados
	*
	* @private 
	*/
	private $__dados = array();

   /**
	* __dados
	*
	* @protected 
	*/
	protected $_modules = array();

   /**
	* _enable_crud
	*
	* @protected 
	*/
	protected $_enable_crud = false;

   /**
	* _model
	*
	* @protected 
	*/
	protected $_model = false;

   /**
	* _public
	*
	* @protected 
	*/
	protected $_public = false;

   /**
	* __construct
	* 
	* constructor method
	* 
	* @author Gustavo Vilas Boas
	* @since 02-2017
	*/
    public function __construct() {
        parent::__construct();

		// carrega a libray
		$this->load->library( [ 'Auth', 'Permissoes_lib' ] );

		// verifica se é public
		if ( !$this->_public && !$this->auth->is_logged() ) {
			redirect( site_url( 'login' ) );
			exit();
		} else {
			$this->_set( 'user', $this->auth->user() );
			$this->_set( 'menu', $this->permissoes_lib->getMenu( $this->router->fetch_class() ) );
		}

		// carrega a model
		$this->load->model('Eventos_model');
		$this->Eventos_model->deleteOld();

		// seta o css e o js
		$this->_set( 'css', array() );
		$this->_set( 'js', array() );
		
		// carrega os modulos
		$this->config->load('bower');
		$this->_modules = $this->config->item( 'autoload' );
    }

   /**
	* __loadModules
	* 
	* carrega os modulos css e js
	* 
	* @author Gustavo Vilas Boas
	* @since 02-2017
	*/
	private function __loadModules() {
		
		// percorre todos os modulos
		foreach ( $this->_modules as $module ) {

			// carrega o modulo
			$item = $this->config->item( $module );

			// adiciona os css
			if ( isset( $item['css'] ) ) {
				$this->_set( 'css', array_merge( $this->_get( 'css' ), $item['css'] ) );
			}

			// adiciona os js
			if ( isset( $item['js'] ) ) {
				$this->_set( 'js', array_merge( $this->_get( 'js' ), $item['js'] ) );
			}
		}
	}

	/**
	* _use
	* 
	* adiciona um novo modulo a ser usado
	* 
	* @author Gustavo Vilas Boas
	* @since 02-2017
	*/
	protected function _use( $module ) {

		// verifica se é um array
		$module = is_array( $module ) ? $module : array( $module );

		//percorre todos os modulos
		foreach ( $module as $m ) {

			// verifica se o modulo ja nao foi adicionado
			if ( in_array( $m, $this->_modules ) ) return true;

			// adiciona o modulo
			$this->_modules[] = $m;
		}
	}

   /**
	* set
	* 
	* seta um novo item nos dados
	* 
	* @author Gustavo Vilas Boas
	* @since 02-2017
	*/
	protected function _set( $key, $value ) {
		$this->__dados[$key] = $value;
	}

   /**
	* get
	* 
	* pega o valor de um dado
	* 
	* @author Gustavo Vilas Boas
	* @since 02-2017
	*/
	protected function _get( $key ) {
		return ( isset( $this->__dados[$key] ) ) ? $this->__dados[$key] : false;
	}

   /**
	* __setupCrud
	* 
	* pega a tabela, o css e o js do crud
	* 
	* @author Gustavo Vilas Boas
	* @since 02-2017
	*/
	public function __setupCrud( $file = false ) {

		// define o nome da model
		$model = $this->_model;

		// carrega a model
		$this->load->model( $model );

		// carrega o output
		$output = ( $file ) ? $file : $this->$model->getCrudOutput();

		// verifica se existem arquivos javascript
		if ( isset( $output['js_files'] ) ) {
			$this->_set( 'js', array_merge( $this->_get( 'js' ), $output['js_files'] ) );
		}

		// verifica se existem arquivos javascript
		if ( isset( $output['css_files'] ) ) {
			$this->_set( 'css', array_merge( $this->_get( 'css' ), $output['css_files'] ) );
		}

		// retorna o output
		return $output['output'];
	}

   /**
	* show
	* 
	* exibe uma view especifica
	* 
	* @author Gustavo Vilas Boas
	* @since 02-2017
	*/
	protected function _show( $view, $use_master = true, $html = false ) {

		// carrega os modulos
		$this->__loadModules();

		// verifica se o crud esta enabled
		if ( $this->_enable_crud ) {
			$this->_set( 'crud_output', $this->__setupCrud() );
		}

		// verifica se deve carregar na master
		if ( $use_master ) {

			// seta o wrapper e carrega a master
			$this->_set( 'wrapper', $view );
			return $this->load->view( 'master', $this->__dados, $html );
		} else {

			// carrega a view diretamente
			return $this->load->view( $view, $this->__dados, $html );
		}
	}
}
