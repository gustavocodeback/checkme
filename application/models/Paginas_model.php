<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Paginas_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'paginas';

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
            'column' => 'titulo', 
            'label'  => 'Titulo'       
        ], [
            'column'  => 'corpo', 
            'label'   => 'Corpo',       
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
