<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Classificacoes_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'classificacoes';

   /**
	* _table_id
	* 
	* @protected
	*/
    protected $_table_id = 'classificao_id';

   /**
	* _fields
	* 
	* @protected
	*/
	protected $_fields = [
        [
            'column' => 'desc_classificacao', 
            'label'  => 'Classificação'       
        ],[
            'column' => 'desc_icone', 
            'label'  => 'Icone'       
        ],[
            'column' => 'vr_ordem', 
            'label'  => 'Ordem'       
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
	* getSorted
	* 
	* pega todos os registros
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function getSorted( ){
		
		// faz a busca
		$this->db->select( '*' );
		$this->db->from( $this->_table );
		$this->db->order_by('vr_ordem', 'ASC');
		$query = $this->db->get();

		// verifica se existem resultados
		return ( $query->num_rows() > 0 ) ? (array) $query->result_array() : false;
	}
}
