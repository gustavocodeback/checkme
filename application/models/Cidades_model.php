<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cidades_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_routine = 'Cidades';

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'cidades';

   /**
	* _table_id
	* 
	* @protected
	*/
    protected $_table_id = 'id';

   /**
	* _fields
	* 
	* @protected
	*/
	protected $_fields = [
        [
            'column' => 'cidade', 
            'label'  => 'Cidade'       
        ], [
            'column' => 'status', 
            'label'  => 'Status'       
        ]
    ];

   /**
	* __construct
	* 
	* constructor method
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function __construct() {
        parent::__construct();
		
		$this->_crud->change_field_type('status','dropdown', ['A' => 'Ativo', 'N' => 'Inativo']);
    }
}
