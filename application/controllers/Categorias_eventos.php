<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias_eventos extends MY_Controller {

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
	protected $_model = 'Categorias_eventos_model';

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
		
		// exibe o login
		$this->_show('crud');
	}
}
