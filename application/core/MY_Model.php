<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model { 

   /**
	* _routine
	*
	* @protected 
	*/
	protected $_routine  = false;

   /**
	* _table
	*
	* @protected 
	*/
	protected $_table  = 'table_name';

   /**
	* _fields
	*
	* @protected 
	*/
	protected $_fields = [];

	/**
	* _relations
	*
	* @protected 
	*/
	protected $_relations = [];

   /**
	* _crud
	*
	* @protected 
	*/
	protected $_crud;

   /**
	* _table_id
	*
	* @protected 
	*/
	protected $_table_id = 'table_id';

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

		// inicia o grocey crud
		$this->load->library( [ 'grocery_CRUD', 'Permissoes_lib' ]);
		$this->_crud = new grocery_CRUD();
		$this->_crud->set_theme('datatables');
		$this->_crud->set_table( $this->_table );

		// verifica se existe uma rotina
		if ( $this->_routine ) $this->__setPermissions();

		// inicia as colunas
		$this->__setColumns();
    }

   /**
	* __setPermissions
	* 
	* seta as permissoes do usuario no modulo
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	private function __setPermissions() {

		// permissao para adicionar
		if ( !$this->permissoes_lib->hasPermission( 'criar', $this->_routine ) ) {
			$this->_crud->unset_add();
		}

		// permissao para adicionar
		if ( !$this->permissoes_lib->hasPermission( 'ver', $this->_routine ) ) {
			$this->_crud->unset_read();
		}

		// permissao para adicionar
		if ( !$this->permissoes_lib->hasPermission( 'editar', $this->_routine ) ) {
			$this->_crud->unset_edit();
		}

		// permissao para adicionar
		if ( !$this->permissoes_lib->hasPermission( 'excluir', $this->_routine ) ) {
			$this->_crud->unset_delete();
		}
	}

   /**
	* __setColumns
	* 
	* seta as colunas da tabela
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	private function __setColumns() {

		// pega todos as colunas
		$args = array_filter($this->_fields, function ( $value ){
			return ( !isset( $value['display'] ) || $value['display'] ) ?  $value['column'] : false;
		});

		// pega a coluna
		$args = array_column( $args, 'column' );

		// seta as colunas no grocey model
		call_user_func_array( array($this->_crud, 'columns' ), $args );

		// seta a exibição das colunas
		$this->__setDisplay();

		// seta as relacoes
		$this->__setRelations();
	}

   /**
	* __setDisplay
	* 
	* seta o nome de exibição
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	private function __setDisplay() {

		// percorre todos os campos
		foreach ( $this->_fields as $field ) {
			if ( !isset( $value['display'] ) || $value['display'] ) 
				$this->_crud->display_as( $field['column'], $field['label'] );
		}
	}

	/**
	* __setRelations
	* 
	* seta os campos relacionais
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	private function __setRelations() {

		// percorre e seta todas as relacoes
		foreach( $this->_relations as $relation ) {

			// verifica se é uma relacao n:n
			if ( isset( $relation['type'] ) && $relation['type'] === 'n:n' ) {

				// seta a relacao n:n
				$this->_crud->set_relation_n_n( $relation['label'],
												$relation['relation_table'],
												$relation['select_table'],
												$this->_table_id,
												$relation['relation_id'],
												$relation['field'] );
			} else {
				
				// seta a relacao 1:n
				$this->_crud->set_relation( $relation['field'], 
											$relation['table'], 
											$relation['use']);
			}
		}
	}

   /**
	* getCrudOutput
	* 
	* gera o crud
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function getCrudOutput() {
		return (array) $this->_crud->render();
	}

   /**
	* create
	* 
	* insere um novo dado
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function create( $dados ){
		return $this->db->insert( $this->_table, $dados );
	}

   /**
	* update
	* 
	* atualiza um dado
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function update( $dados ) {

		// prepara os dados
		$this->db->where( $this->_table_id, $dados['id']);

		// deleta o id
		unset( $dados['id'] );
		if ( isset( $dados[$this->_table_id] ) ) unset( $dados[$this->_table_id] );

		// faz o update
		return $this->db->update($this->_table, $dados); 
	}

   /**
	* delete
	* 
	* deleta um dado
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function delete( $id ) {
		$this->db->where( $this->_table_id, $id );
		return $this->db->delete( $this->_table ); 
	}

	/**
	* getById
	* 
	* pega um dado por id
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function getById( $id ){
		
		// faz a busca
		$this->db->select( '*' );
		$this->db->from( $this->_table );
		$this->db->where( [$this->_table_id => $id ] );
		$query = $this->db->get();

		// verifica se existem resultados
		return ( $query->num_rows() > 0 ) ? (array)$query->result()[0] : false;
	}

	/**
	* getAll
	* 
	* pega todos os registros
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/

	public function getAll( $where = false, $join = false, $offset = false ) {
		
		// monta a busca
		if ( !$join ) {
			$this->db->select( '*' );
			$this->db->from( $this->_table );
		} else {
			$this->db->select( 'a.*, b.*');
			$this->db->from( $this->_table.' a' );
			$this->db->join( $join['table'].' b', $join['where'], 'left' );
		}

		// verifica se existe um limit
		if ( $offset != false ) {
			$this->db->limit( 20, $offset );
		}
		
		//verifica se existe um where
		if ( $where ) $this->db->where( $where );

		// pega os dados do banco
		$query = $this->db->get();

		// verifica se existem resultados
		return ( $query->num_rows() > 0 ) ? $query->result_array() : false;
	}	

	/**
	* table
	* 
	* pega a tabela atual
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
	public function table() {
		return $this->_table;
	}
}