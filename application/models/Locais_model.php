<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Locais_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'locais';

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
            'column' => 'cidade_id', 
            'label'  => 'Cidade'       
        ], [
            'column' => 'usuario_id', 
            'label'  => 'Usuario',
            'display' => false         
        ], [
            'column' => 'categoria_id', 
            'label'  => 'Categoria'       
        ], [
            'column'  => 'nome', 
            'label'   => 'Nome',       
        ], [
            'column' => 'descricao', 
            'label'  => 'Descrição',
            'display' => false          
        ], [
            'column' => 'foto', 
            'label'  => 'Foto'       
        ], [
            'column' => 'funcionamento', 
            'label'  => 'Horário de funcionamento',
            'display' => false         
        ], [
            'column' => 'cnpj', 
            'label'  => 'CNPJ',       
        ], [
            'column' => 'cep', 
            'label'  => 'CEP',
            'display' => false         
        ], [
            'column' => 'endereco', 
            'label'  => 'Endereço',
            'display' => false         
        ], [
            'column' => 'telefone', 
            'label'  => 'Telefone',
            'display' => false         
        ], [
            'column' => 'email', 
            'label'  => 'E-mail',
            'display' => false       
        ], [
            'column' => 'latitude', 
            'label'  => 'Latitude',
            'display' => false         
        ], [
            'column' => 'longitude', 
            'label'  => 'Longitude',
            'display' => false         
        ], [
            'column' => 'tipo', 
            'label'  => 'Tipo',
            'display' => false       
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
            'field' => 'cidade_id',
            'table' => 'cidades',
            'use'   => 'cidade'
        ], [
            'field' => 'usuario_id',
            'table' => 'usuarios',
            'use'   => 'email'
        ], [
            'field' => 'categoria_id',
            'table' => 'categorias_lugares',
            'use'   => 'categoria'
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

        // seta o campo da senha
        $this->_crud->change_field_type('latitude','hidden');
        $this->_crud->change_field_type('longitude','hidden');
        $this->_crud->set_field_upload('foto', 'uploads');
        $this->_crud->change_field_type('status','dropdown', ['A' => 'Ativo', 'N' => 'Inativo']);
        $this->_crud->change_field_type('tipo','dropdown', ['E' => 'Estabelecimento', 'L' => 'Local']);
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
	* obterLugares
	* 
	* obter os lugares
	* 
	*/
    public function obterLugares( $pagina = 1, $busca = '', $tipo, $cidade_id ) {

        // seta a pagina
        $pagina = ( $pagina - 1 ) * 5;

        // monta a busca
        $this->db->from( 'locais l' )
        ->select( 'l.*, c.categoria' )
        ->join( 'categorias_lugares c', 'c.id = l.categoria_id' )
        ->where( "  l.status <> 'N' AND 
                    tipo = '$tipo' AND 
                    l.cidade_id = $cidade_id AND
                    ( l.nome LIKE '%$busca%' OR c.categoria LIKE '%$busca%' )" );

        // seta as paginas
        $this->db->limit( 5, $pagina );

        // faz a busca
        $busca = $this->db->get();

        // volta os resultados
        return $busca->result_array();
    }
}
