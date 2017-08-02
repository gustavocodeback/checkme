<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias_bandas_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_routine = 'Categorias Bandas';

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'categorias_bandas';

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
            'column' => 'categoria', 
            'label'  => 'Categoria'       
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
