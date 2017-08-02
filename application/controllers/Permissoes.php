<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permissoes extends MY_Controller {

   /**
	* _public
	* 
	* @protected
	*/
	protected $_public = true;

    /**
	* _enable_crud
	*
	* @protected 
	*/
	protected $_enable_crud = true;

   /**
	* _model
	*
	* @protected 
	*/
	protected $_model = 'Permissoes_model';

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
            $this->_set( 'add_url', site_url( 'permissoes/index/add' ) );
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
		
		// exibe o login
		$this->_show('crud');
	}
}
