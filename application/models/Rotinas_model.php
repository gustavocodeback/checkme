<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rotinas_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'rotinas';

   /**
	* _table_id
	* 
	* @protected
	*/
    protected $_table_id = 'rotina_id';

   /**
	* _fields
	* 
	* @protected
	*/
	protected $_fields = [
        [
            'column' => 'desc_rotina', 
            'label'  => 'Rotinas'       
        ],[
            'column' => 'classificacao_id', 
            'label'  => 'Classificacao'       
        ],[
            'column' => 'desc_link', 
            'label'  => 'Link'       
        ]
    ];

	/**
	* _relations
	*
	* @protected 
	*/
	protected $_relations = [
        [
            'field' => 'classificacao_id',
            'table' => 'classificacoes',
            'use'   => 'desc_classificacao'
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
	* getById
	* 
	* pega um dado por id
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function getByClassificacaoId( $id ){
		
		// faz a busca
		$this->db->select( '*' );
		$this->db->from( $this->_table );
		$this->db->where( [ 'classificacao_id' => $id ] );
		$query = $this->db->get();

		// verifica se existem resultados
		return ( $query->num_rows() > 0 ) ? $query->result_array() : false;
	}

   /**
	* getRoutine
	* 
	* pega os dados de uma rotina
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function getRoutine( $routine, $cargo_id ) {
		
		// faz a busca
		$this->db->select( '*' );
		$this->db->from( $this->_table );
		$this->db->where( [ 'desc_rotina' => $routine, 'cargo_id' => $cargo_id ] );
		$this->db->join('permissoes', 'permissoes.rotina_id = rotinas.rotina_id');
		$query = $this->db->get();

		// verifica se existem resultados
		return ( $query->num_rows() > 0 ) ? $query->result_array()[0] : false;
	}
}
