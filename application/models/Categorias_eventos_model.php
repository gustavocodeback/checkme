<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias_eventos_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_routine = 'Categorias Eventos';

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'categorias_eventos';

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

	/**
	* getAll
	* 
	* constructor method
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function getAll( $where = false, $join = false, $offset = false ) {
		
		// faz a busca das categorias
		$this->db->from( $this->_table ) 
		->select('*')
		->order_by("categoria", "asc");

		// pega os dados do banco
		$query = $this->db->get();

		// verifica se existem resultados
		return ( $query->num_rows() > 0 ) ? $query->result_array() : false;
	}
}
