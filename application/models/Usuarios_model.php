<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends MY_Model {

   /**
	* _table
	* 
	* @protected
	*/
    protected $_routine = 'Usuários';

   /**
	* _table
	* 
	* @protected
	*/
    protected $_table = 'usuarios';

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
            'column' => 'cidade', 
            'label'  => 'Cidade'       
        ], [
            'column' => 'nome', 
            'label'  => 'Nome'       
        ], [
            'column' => 'email', 
            'label'  => 'E-mail'       
        ], [
            'column' => 'senha', 
            'label'  => 'Senha',
            'display' => false         
        ], [
            'column' => 'descricao', 
            'label'  => 'Descrição',
            'display' => false         
        ], [
            'column' => 'genero', 
            'label'  => 'Gênero'       
        ], [
            'column' => 'aniversario', 
            'label'  => 'Aniversário',
            'display' => false         
        ], [
            'column' => 'foto', 
            'label'  => 'Foto'       
        ], [
            'column' => 'token', 
            'label'  => 'Token',
            'display' => false         
        ], [
            'column' => 'token_senha', 
            'label'  => 'Token Senha',
            'display' => false         
        ], [
            'column' => 'status', 
            'label'  => 'Status'       
        ], [
            'column' => 'api', 
            'label'  => 'API',
            'display' => false         
        ], [
            'column' => 'facebookid', 
            'label'  => 'Facebook',
            'display' => false         
        ], [
            'column' => 'facebooktoken', 
            'label'  => 'Facebook Token',
            'display' => false         
        ], [
            'column' => 'latitude', 
            'label'  => 'Latitude',
            'display' => false         
        ], [
            'column' => 'longitude', 
            'label'  => 'Longitude',
            'display' => false         
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
        
        // seta os campos escondidos
        $this->_crud->change_field_type('token_senha','hidden');
        $this->_crud->change_field_type('token','hidden');
        $this->_crud->change_field_type('facebookid','hidden');
        $this->_crud->change_field_type('api','hidden');
        $this->_crud->change_field_type('facebooktoken','hidden');
        $this->_crud->change_field_type('latitude','hidden');
        $this->_crud->change_field_type('longitude','hidden');

        // seta os tipos especiais de campo
        $this->_crud->change_field_type('senha','password');
        $this->_crud->set_field_upload('foto', 'uploads');
		
        // seta os dropdowns
        $this->_crud->change_field_type('status','dropdown', ['A' => 'Ativo', 'N' => 'Inativo']);
		$this->_crud->change_field_type('genero','dropdown', ['F' => 'Feminino', 'M' => 'Masculino']);

        // seta os callbacks
        $this->_crud->callback_before_insert( array( $this, 'hashPassword' ) );
        $this->_crud->callback_before_update( array( $this, 'hashPassword' ) );
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
            if ( $user['senha'] === $value['senha']) return $value;
        }

        // volta o objeto formatado
        $value['senha'] = md5( $value['senha'] );

        // retorno
        return $value;
    }

    /**
	* getUserByEmail
	* 
	* pega um usuario pelo id
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function getUserByFacebook( $email ) {

        // faz a busca
		$this->db->select( '*' );
		$this->db->from( $this->_table );
		$this->db->where( ['facebookid' => $email ] );
		$query = $this->db->get();

		// verifica se existem resultados
		return ( $query->num_rows() > 0 ) ? (array)$query->result()[0] : false;
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
		$this->db->where( ['email' => $email ] );
		$query = $this->db->get();

		// verifica se existem resultados
		return ( $query->num_rows() > 0 ) ? (array)$query->result()[0] : false;
    }

   /**
	* login
	* 
	* faz o login
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function login( $email, $senha ) {

        // faz a busca no banco
        $this->db->from( $this->_table )
        ->select( 'id, nome, email, foto, cidade, genero, token, aniversario' )
        ->where( "( senha = '".md5( $senha )."' OR token_senha = '$senha' ) AND email = '$email' AND status = 'A' " );
        $query = $this->db->get();

		// verifica se existem resultados
		if ( $query->num_rows() > 0 ) {

            // pega o usuario
            $user = $query->result_array()[0];

            // seta o token
            $token = md5( uniqid( time() * rand() ) );

            // prepara os dados
            $dados = [ 'token' => $token,
                        'id' => $user['id'] ];

            // faz o update
            $this->update( $dados );

            // adiciona o token na resposta
            $user['token'] = $token;
            return $user;
         } else return false;
    }

   /**
	* obterUsuarioPorUid
	* 
	* obtem um usuario pelo uid
	* 
	*/
    public function obterUsuarioPorUid( $uid ) {

        // prepara a query
        $this->db->from( 'usuarios' )
        ->select( '*' )
        ->where( " uid = '$uid' " );

        // faz a busca
        $busca = $this->db->get();

        // volta o resultado
        return ( $busca->num_rows() > 0 ) ? $busca->result_array()[0] : false;
    }
}
