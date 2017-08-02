<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bandas_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_routine = 'Bandas';

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'bandas';

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
            'column' => 'usuario_id', 
            'label'  => 'Usuário'       
        ],[
            'column' => 'categoria_id', 
            'label'  => 'Categoria'       
        ],[
            'column' => 'cidade_id', 
            'label'  => 'Cidade'       
        ],[
            'column' => 'banda', 
            'label'  => 'Banda'       
        ], [
            'column' => 'foto', 
            'label'  => 'Foto'       
        ], [
            'column' => 'descricao', 
            'label'  => 'Descrição'       
        ], [
            'column' => 'contato', 
            'label'  => 'Contato'       
        ], [
            'column' => 'status', 
            'label'  => 'Status'       
        ]
    ];

    /**
	* _relations
	*
	* @protected 
	*/
	protected $_relations = [
        [
            'field' => 'usuario_id',
            'table' => 'usuarios',
            'use'   => 'email'
        ], [
            'field' => 'categoria_id',
            'table' => 'categorias_bandas',
            'use'   => 'categoria'
        ], [
            'field' => 'cidade_id',
            'table' => 'cidades',
            'use'   => 'cidade'
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
        
        $this->_crud->set_field_upload('foto', 'uploads');
		$this->_crud->change_field_type('status','dropdown', ['A' => 'Ativo', 'N' => 'Inativo']);
    }

    public function getAll(  $where = false, $join = false, $offset = false  ) {

        // monta a busca
		if ( !$join ) {
			$this->db->select( '*' );
			$this->db->from( $this->_table );
		} else {
			$this->db->select( 'a.*, b.*' );
			$this->db->from( $this->_table.' a' );
            $join['where'] = $join['where'].' AND b.usuario_id = '.$join['user'];
			$this->db->join( $join['table'].' b', $join['where'], 'left' );
		}

		// verifica se existe um limit
		if ( $offset != false ) {
			$this->db->limit( 20, $offset );
		}
		
		//verifica se existe um where
		if ( $where )  {
            foreach( $where as $chave => $item ) {
                unset( $where[$chave] );
                $chave = str_replace('a.', '', $chave );
                $where['a.'.$chave] = $item;
            }

            $this->db->where( $where );
        }

		// pega os dados do banco
		$query = $this->db->get();

		// verifica se existem resultados
		return ( $query->num_rows() > 0 ) ? $query->result_array() : false;
    }

   /**
	* obterBanda
	* 
	* obtem as bandas
	* 
	*/
    public function obterBandas( $pagina, $busca ) {

        // monta a pagina
        $pagina = ( $pagina - 1 ) * 5;
        // seta a busca
        $this->db->from( 'bandas b' )
        ->select( '*' )
        ->join( 'categorias_bandas c', 'b.categoria_id = c.id'  )
        ->where( " b.banda LIKE '%$busca%' OR c.categoria LIKE '%$busca%' " )
        ->limit( 5, $pagina )
        ->order_by( 'b.id', 'DESC' );

        // faz a busca
        $busca = $this->db->get();

        // retorna os dados
        return $busca->result_array();
    }
}
