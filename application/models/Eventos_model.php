<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_routine = 'Eventos';

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'eventos';

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
            'column' => 'categoria_id', 
            'label'  => 'Categoria'       
        ], [
            'column' => 'local_id', 
            'label'  => 'Local',
            'display'=> false         
        ], [
            'column' => 'usuario_id', 
            'label'  => 'Usuário'       
        ], [
            'column' => 'cidade_id', 
            'label'  => 'Cidade'       
        ], [
            'column' => 'banda_id', 
            'label'  => 'Banda',
            'display'=> false     
        ], [
            'column' => 'nome', 
            'label'  => 'Nome'       
        ], [
            'column' => 'descricao', 
            'label'  => 'Descrição',
            'display'=> false         
        ], [
            'column' => 'foto', 
            'label'  => 'Foto'       
        ], [
            'column' => 'data', 
            'label'  => 'Data'       
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
            'field' => 'categoria_id',
            'table' => 'categorias_eventos',
            'use'   => 'categoria'
        ],[
            'field' => 'cidade_id',
            'table' => 'cidades',
            'use'   => 'cidade'
        ],[
            'field' => 'local_id',
            'table' => 'locais',
            'use'   => 'nome'
        ],[
            'field' => 'usuario_id',
            'table' => 'usuarios',
            'use'   => 'email'
        ],[
            'field' => 'banda_id',
            'table' => 'bandas',
            'use'   => 'banda'
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
			$this->db->select( 'a.*, b.*, c.email as usuario');
			$this->db->from( $this->_table.' a' );
            $join['where'] = $join['where'].' AND b.usuario_id = '.$join['user'];
			$this->db->join( $join['table'].' b', $join['where'], 'left' );
		}

        $this->db->join( 'usuarios c', 'c.id = a.usuario_id', 'left' );

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
        $this->db->order_by("data", "asc"); 

		// pega os dados do banco
		$query = $this->db->get();

		// verifica se existem resultados
		return ( $query->num_rows() > 0 ) ? $query->result_array() : false;
    }

    public function deleteOld() {
        $this->db->where( 'data <', date('Y-m-d', time() ) );
		return $this->db->delete( $this->_table ); 
    }

   /**
	* obterLugares
	* 
	* obter os lugares
	* 
	*/
    public function obterEventos( $pagina, $busca, $cidade_id ) {

        // seta a pagina
        $pagina = ( $pagina - 1 ) * 5;

        // monta a busca
        $this->db->from( 'eventos e' )
        ->select( 'e.*, c.categoria, u.email ' )
        ->join( 'categorias_eventos c', 'c.id = e.categoria_id' )
        ->join( 'usuarios u', 'u.id = e.usuario_id', 'left' )
        ->where( "  e.status <> 'N' AND  
                    e.cidade_id = $cidade_id AND
                    e.data > '".date( 'Y-m-d', time() )."' AND
                    ( e.nome LIKE '%$busca%' OR c.categoria LIKE '%$busca%' )" );

        // ordena pela data
        $this->db->order_by( 'data', 'ASC' );

        // seta as paginas
        $this->db->limit( 5, $pagina );

        // faz a busca
        $busca = $this->db->get();

        // volta os resultados
        return $busca->result_array();
    }

   /**
	* obterMeusEventos
	* 
	* obter os lugares
	* 
	*/
    public function obterMeusEventos( $pagina, $user_id ) {

        // seta a pagina
        $pagina = ( $pagina - 1 ) * 5;

        // monta a busca
        $this->db->from( 'eventos e' )
        ->select( 'e.*, c.categoria' )
        ->join( 'categorias_eventos c', 'c.id = e.categoria_id' )
        ->where( "  e.status <> 'N' AND  
                    e.usuario_id = $user_id " );

        // ordena pela data
        $this->db->order_by( 'data', 'ASC' );

        // seta as paginas
        $this->db->limit( 5, $pagina );

        // faz a busca
        $busca = $this->db->get();

        // volta os resultados
        return $busca->result_array();
    }

    /**
	* obterEventosLugar
	* 
	* obter os eventos de um lugar
	* 
	*/
    public function obterEventosLugar( $pagina, $lugar_id ) {

        // seta a pagina
        $pagina = ( $pagina - 1 ) * 5;

        // monta a busca
        $this->db->from( 'eventos e' )
        ->select( '*' )
        ->join( 'categorias_eventos c', 'c.id = e.categoria_id' )
        ->where( "  e.status <> 'N' AND  
                    e.local_id = $lugar_id " );

        // ordena pela data
        $this->db->order_by( 'data', 'ASC' );

        // seta as paginas
        $this->db->limit( 5, $pagina );

        // faz a busca
        $busca = $this->db->get();

        // volta os resultados
        return $busca->result_array();
    }
}
