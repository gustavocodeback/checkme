<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permissoes_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'permissoes';

   /**
	* _table_id
	* 
	* @protected
	*/
    protected $_table_id = 'permissao_id';

   /**
	* _fields
	* 
	* @protected
	*/
	protected $_fields = [
        [
            'column' => 'rotina_id', 
            'label'  => 'Rotina'       
        ],[
            'column' => 'cargo_id', 
            'label'  => 'Cargo'       
        ],[
            'column' => 'flg_criar', 
            'label'  => 'Criar'       
        ],[
            'column' => 'flg_ver', 
            'label'  => 'ver'       
        ],[
            'column' => 'flg_editar', 
            'label'  => 'Editar'       
        ],[
            'column' => 'flg_excluir', 
            'label'  => 'Excluir'       
        ]
    ];

    /**
	* _relations
	*
	* @protected 
	*/
	protected $_relations = [
        [
            'field' => 'rotina_id',
            'table' => 'rotinas',
            'use'   => 'desc_rotina'
        ], [
            'field' => 'cargo_id',
            'table' => 'cargos',
            'use'   => 'desc_cargo'
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

        // seta os campos
        $this->_crud->change_field_type('flg_criar','dropdown', ['S' => 'Permitir', 'N' => 'Negar']);
        $this->_crud->change_field_type('flg_ver','dropdown', ['S' => 'Permitir', 'N' => 'Negar']);
        $this->_crud->change_field_type('flg_editar','dropdown', ['S' => 'Permitir', 'N' => 'Negar']);
        $this->_crud->change_field_type('flg_excluir','dropdown', ['S' => 'Permitir', 'N' => 'Negar']);
    }
}
