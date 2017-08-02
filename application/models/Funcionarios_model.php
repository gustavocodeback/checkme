<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Funcionarios_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'funcionarios';

   /**
	* _table_id
	* 
	* @protected
	*/
    protected $_table_id = 'funcionario_id';

   /**
	* _fields
	* 
	* @protected
	*/
	protected $_fields = [
        [
            'column' => 'cargo_id', 
            'label'  => 'Cargo'       
        ], [
            'column' => 'desc_email', 
            'label'  => 'E-mail'       
        ], [
            'column' => 'desc_nome', 
            'label'  => 'Nome'       
        ], [
            'column'  => 'hash_senha', 
            'label'   => 'Senha',
            'display' => false       
        ], [
            'column' => 'hash_token', 
            'label'  => 'Token',
            'display' => false        
        ], [
            'column' => 'date_criado', 
            'label'  => 'Criado'       
        ], [
            'column' => 'date_ultimo_login', 
            'label'  => 'Login'       
        ], [
            'column' => 'flg_ativo', 
            'label'  => 'Ativo'       
        ], [
            'column' => 'hash_foto', 
            'label'  => 'Foto'       
        ]
    ];

   /**
	* _relations
	*
	* @protected 
	*/
	protected $_relations = [
        [
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

        // seta o campo da senha
        $this->_crud->change_field_type('hash_senha','password');
        $this->_crud->change_field_type('date_ultimo_login','hidden');
        $this->_crud->change_field_type('date_criado','hidden');
        $this->_crud->change_field_type('hash_token','hidden');
        $this->_crud->set_field_upload('hash_foto', 'uploads');
        $this->_crud->change_field_type('flg_ativo','dropdown', ['A' => 'Ativo', 'N' => 'Inativo']);

        // seta os callbacks
        $this->_crud->callback_before_insert( array( $this, 'hashPassword' ) );
        $this->_crud->callback_before_update( array( $this, 'hashPassword' ) );
    }

   /**
	* getUserByEmail
	* 
	* pega um usuario pelo id
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function getUserByEmail( $email ) {

        // faz a busca
		$this->db->select( '*' );
		$this->db->from( $this->_table );
		$this->db->where( ['desc_email' => $email ] );
		$query = $this->db->get();

		// verifica se existem resultados
		return ( $query->num_rows() > 0 ) ? (array)$query->result()[0] : false;
    }

    /**
	* hashPassword
	* 
	* enripta a senha
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function hashPassword( $value, $primary_key = null ) {
        
        // verifica se não é nulo
        if ( !is_null( $primary_key ) ) {
            $user = $this->getById( $primary_key );
            
            // verifica se a senhas são iguais
            if ( $user['hash_senha'] === $value['hash_senha']) return $value;
        }

        // carrega a biblioteca de crypt
        $this->load->library( 'Bcrypt' );

        // volta o objeto formatado
        $value['hash_senha'] = $this->bcrypt->hash( $value['hash_senha'] );

        // retorno
        return $value;
    }
}
