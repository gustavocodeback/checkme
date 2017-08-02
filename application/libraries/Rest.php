<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rest {

   /**
	* _ci
	*
	* @private 
	*/
    private $_ci;

   /**
	* __construct
	* 
	* metodo construtor
	* 
	* @author Gustavo Vilas Boas
	* @since 12-2016
	*/
    public function __construct() {
        
        // instancia do codeigniter
        $this->_ci =& get_instance();
    }

   /**
	* header
	* 
	* pega um header da requisicao
	* 
	*/
    public function header( $key ) {

        // pega os headers da requisição
        $token = isset($_SERVER['HTTP_'.strtoupper( $key )] ) ? $_SERVER['HTTP_'.strtoupper( $key )] : null;

        // pega os headers da requisição
        $token = $this->_ci->input->get_request_header( $key ) ? $this->_ci->input->get_request_header( $key ) : $token;

        // volta o header
        return $token;
    }

   /**
	* send
	* 
	* envia uma resposta
	* 
	*/
    public function send( $status, $data ) {
        
        // prepara a resposta
        $res = [
            'code' => $status,
            'data' => $data
        ];

        // envia a resposta
        echo json_encode( $res );
        return null;
    }

   /**
	* send
	* 
	* envia erro
	* 
	*/
    public function reject( $msg ) {

        // envia o erro
        return $this->send( '400', $msg );
    }

   /**
	* resolve
	* 
	* envia sucesso
	* 
	*/
    public function resolve( $data ) {

        // envia o erro
        return $this->send( '200', $data );
    }

   /**
	* denied
	* 
	* envia o acesso negado
	* 
	*/
    public function denied()  {
        $this->send( '403', 'Acesso negado' );
        exit();
    }

   /**
	* user
	* 
	* pega o usuario que fez a requisicao
	* 
	*/
    public function user() {

        // pega o uid
        $uid = $this->header( 'auth_uid' );
        $email = $this->header( 'auth_email' );

        // prepara a query
        $this->_ci->db->from( 'usuarios' )
        ->select( '*' )
        ->where( " uid = '$uid' " );

        // faz a busca
        $busca = $this->_ci->db->get();

        // retorna os dados
        if ( $busca->num_rows() > 0 ) {

            // pega o primeiro resultado
            $user = $busca->result_array()[0];

            // verifica se os emails batem
            if ( $user['email'] == $email ) {
                return $user;
            } else return false;
        } else return false;
    }

   /**
	* authorize
	* 
	* verifica se o usuario esta autorizado
	* 
	*/
    public function authorize() {
        
        // se nao tiver usuario logado
        if ( !$this->user() ) {

            // exibe o acesso negado
            $this->denied();
            return false;
        } else return true;
    }
}

/* end of file */