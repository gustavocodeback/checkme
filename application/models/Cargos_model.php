<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cargos_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_routine = 'Cargos';

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'cargos';

   /**
	* _table_id
	* 
	* @protected
	*/
    protected $_table_id = 'cargo_id';

   /**
	* _fields
	* 
	* @protected
	*/
	protected $_fields = [
        [
            'column' => 'desc_cargo', 
            'label'  => 'Cargo'       
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
    }
}
