<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Interacoes_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_routine = 'Interações';

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'interacoes';

   /**
	* _table_id
	* 
	* @protected
	*/
    protected $_table_id = 'interacao_id';

   /**
	* _fields
	* 
	* @protected
	*/
	protected $_fields = [
        [
            'column' => 'lugar_id', 
            'label'  => 'Lugar'       
        ], [
            'column' => 'interacao', 
            'label'  => 'Interação'       
        ], [
            'column' => 'evento_id', 
            'label'  => 'Evento'       
        ], [
            'column' => 'banda_id', 
            'label'  => 'Banda'       
        ], [
            'column' => 'usuario_id', 
            'label'  => 'Usuario'       
        ]
    ];

    /**
	* _relations
	*
	* @protected 
	*/
	protected $_relations = [
            [
                'field' => 'lugar_id',
                'table' => 'locais',
                'use'   => 'nome'
            ],[
                'field' => 'evento_id',
                'table' => 'eventos',
                'use'   => 'nome'
            ],[
                'field' => 'banda_id',
                'table' => 'bandas',
                'use'   => 'banda'
            ],[
                'field' => 'usuario_id',
                'table' => 'usuarios',
                'use'   => 'email'
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
		
		$this->_crud->change_field_type('interacao','dropdown', ['F' => 'Favorito', 'G' => 'Gostei']);
    }

   /**
	* favoritou
	* 
	* verifica se um usuario favoritou um item
	* 
	*/
    public function favoritou( $user, $chave, $valor ) {

        // prepara a busca
        $this->db->from( 'interacoes' )
        ->select( 'interacao_id' )
        ->where( " interacao = 'F' AND $chave = $valor AND usuario_id = $user " );

        // faz a busca
        $busca = $this->db->get();

        // volta os dados
        return ( $busca->num_rows() > 0 ) ? true : false;
    }

   /**
	* desfavoritar
	* 
	* desfavorita um item um item
	* 
	*/
    public function desfavoritar( $user, $chave, $valor ) {

        // monta o where
        $this->db->where( " interacao = 'F' AND usuario_id = $user AND $chave = $valor " );

        // retira o item
        return $this->db->delete( 'interacoes' );
    }
}
